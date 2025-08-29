/* eslint-disable no-unused-vars */
// eslint-disable-next-line no-undef
var $ = jQuery.noConflict();

let UomoSections = {};
let UomoElements = {};

let UomoSelectors = {
  pageBackDropActiveClass: 'page-overlay_visible',
  quantityControl: '.qty-control',
  scrollToTopId: 'scrollTop',
  $pageBackDrop: document.querySelector('.page-overlay'),
  scrollWidth:   window.innerWidth - document.body.clientWidth + 'px',
  jsContentVisible: '.js-content_visible',
  starRatingControl: '.star-rating .star-rating__star-icon',
}

// Utility functions
let UomoHelpers = {
  isMobile: false,
  sideStkEl: {},

  debounce: (callback, wait, immediate = false) => {
    let timeout = null;

    return function() {
      const callNow = immediate && !timeout;
      const next = () => callback.apply(this, arguments);

      clearTimeout(timeout);
      timeout = setTimeout(next, wait);

      if (callNow) {
        next();
      }
    }
  },

  showPageBackdrop: () => {
    UomoSelectors.$pageBackDrop && UomoSelectors.$pageBackDrop.classList.add(UomoSelectors.pageBackDropActiveClass);
    document.body.classList.add('overflow-hidden');
    document.body.style.paddingRight = UomoSelectors.scrollWidth;
    document.querySelectorAll('.header_sticky, .footer-mobile').forEach(element => {
      element.style.borderRight = UomoSelectors.scrollWidth + ' solid transparent';
    });
  },

  hidePageBackdrop: () => {
    UomoSelectors.$pageBackDrop && UomoSelectors.$pageBackDrop.classList.remove(UomoSelectors.pageBackDropActiveClass);
    document.body.classList.remove('overflow-hidden');
    document.body.style.paddingRight = '';
    document.querySelectorAll('.header_sticky, .footer-mobile').forEach(element => {
      element.style.borderRight = '';
    });
  },

  hideHoverComponents: () => {
    document.querySelectorAll(UomoSelectors.jsContentVisible).forEach( el => {
      el.classList.remove(UomoSelectors.jsContentVisible.substring(1));
    });
  },

  updateDeviceSize: () => {
    return window.innerWidth < 992
  }
};

function purecookieDismiss() {
  setCookie("purecookieDismiss", "1", 7), pureFadeOut("cookieConsentContainer")
}

function setCookie(e, o, i) {
  var t = "";
  if (i) {
    var n = new Date;
    n.setTime(n.getTime() + 24 * i * 60 * 60 * 1e3), t = "; expires=" + n.toUTCString()
  }
  document.cookie = e + "=" + (o || "") + t + "; path=/"
}

function pureFadeOut(e) {
  var o = document.getElementById(e);
  o.style.opacity = 1,
    function e() {
      (o.style.opacity -= .02) < 0 ? o.style.display = "none" : requestAnimationFrame(e)
    }()
}

(function () {
  'use strict';

  // Scroll bar width
  const scrollBarWidth = window.innerWidth - document.body.clientWidth

  // Components appear after click
  UomoElements.JsHoverContent = (function () {
    function JsHoverContent () {
      const visibleClass = UomoSelectors.jsContentVisible.substring(1);

      document.querySelectorAll('.js-hover__open').forEach(el => {
        el.addEventListener('click', (e) => {
          e.preventDefault();

          const $container = e.currentTarget.closest('.hover-container');
          if ($container.classList.contains(visibleClass)) {
            $container.classList.remove(visibleClass);
            // e.stopPropagation();
          } else {
            UomoHelpers.hideHoverComponents();
            $container.classList.add(visibleClass);
          }
        });
      });

      document.addEventListener('click', (e) => {
        if (!e.target.closest(UomoSelectors.jsContentVisible)) {
          UomoHelpers.hideHoverComponents();
        }
      });
    }


    return JsHoverContent;
  })();

  UomoElements.QtyControl = (function () {
    function QtyControl () {
      document.querySelectorAll(UomoSelectors.quantityControl).forEach(function($qty) {
        if ($qty.classList.contains('qty-initialized')) {
          return;
        }

        $qty.classList.add('qty-initialized');
        const $reduce = $qty.querySelector('.qty-control__reduce');
        const $increase = $qty.querySelector('.qty-control__increase');
        const $number = $qty.querySelector('.qty-control__number');

        if ($reduce && $increase && $number) { // Pengecekan keamanan
            $reduce.addEventListener('click', function() {
              $number.value = parseInt($number.value) > 1 ? parseInt($number.value) - 1 : parseInt($number.value);
            });
    
            $increase.addEventListener('click', function() {
              $number.value = parseInt($number.value) + 1;
            });
        }
      });
    }

    return QtyControl;
  })();

  UomoElements.ScrollToTop = (function () {
    function ScrollToTop () {
      const $scrollTop = document.getElementById(UomoSelectors.scrollToTopId);

      if (!$scrollTop) {
        return;
      }

      $scrollTop.addEventListener('click', function(event) {
        event.preventDefault();
        event.stopPropagation();
        window.scrollTo(window.scrollX, 0);
      });

      let scrolled = false;
      window.addEventListener('scroll', function() {
        if ( 250 < window.scrollY && !scrolled ) {
          $scrollTop.classList.remove('visually-hidden');
          scrolled = true;
        }

        if ( 250 > window.scrollY && scrolled ) {
          $scrollTop.classList.add('visually-hidden');
          scrolled = false;
        }
      });
    }

    return ScrollToTop;
  })();

  UomoElements.Search = (function() {
    function Search() {
      // Declare variables
      this.selectors = {
        container: '.search-field',
        inputBox: '.search-field__input',
        searchSuggestItem: '.search-suggestion a.menu-link',
        searchFieldActor: '.search-field__actor',
        resetButton: '.search-popup__reset',
        searchCategorySelector: '.js-search-select',
        resultContainer: '.search-result',
        ajaxURL: './search.html'
      }

      this.searchInputFocusedClass = 'search-field__focused';

      this.$containers = document.querySelectorAll(this.selectors.container);

      this._initSearchSelect();
      this._initSearchReset();
      this._initSearchInputFocus();
      this._initAjaxSearch();

      this._handleAjaxSearch = this._handleAjaxSearch.bind(this);
      this._updateSearchResult = this._updateSearchResult.bind(this);
    }

    Search.prototype = Object.assign({}, Search.prototype, {
      _initSearchSelect: function () {
        const _this = this;
        this.$containers.forEach( el => {
          /**
           * Filter suggestion list on input
           */

          const $inputBox = el.querySelector(_this.selectors.inputBox);
          if ($inputBox) {
            $inputBox.addEventListener('keyup', (e) => {
              const filterValue = e.currentTarget.value.toUpperCase();
              el.querySelectorAll(_this.selectors.searchSuggestItem).forEach( el => {
                const txtValue = el.innerText;

                if (txtValue.toUpperCase().indexOf(filterValue) > -1) {
                  el.style.display = "";
                } else {
                  el.style.display = "none";
                }
              });
            });
          }

          /**
           * Search category selector
           */
          el.querySelectorAll(_this.selectors.searchCategorySelector).forEach( scs => {
            scs.addEventListener('click', function(e) {
              e.preventDefault();
              const $s_f_a = el.querySelector(_this.selectors.searchFieldActor);
              if ($s_f_a) {
                $s_f_a.value = e.target.innerText;
              }
            });
          });
        })
      },

      _removeFormActiveClass($eventEl) {
        const $parentDiv = $eventEl.closest(this.selectors.container);
        if ($parentDiv) {
            $parentDiv.classList.remove(this.searchInputFocusedClass);
        }
      },

      _initSearchReset: function () {
        const _this = this;
        document.querySelectorAll(this.selectors.resetButton).forEach( el => {
          el.addEventListener('click', function(e) {
            const $parentDiv = e.target.closest(_this.selectors.container);
            if ($parentDiv) {
                const $inputBox = $parentDiv.querySelector(_this.selectors.inputBox);
                const $rc = $parentDiv.querySelector(_this.selectors.resultContainer);
    
                if ($inputBox) $inputBox.value = '';
                if ($rc) $rc.innerHTML = '';
                _this._removeFormActiveClass(e.target);
            }
          });
        })
      },

      _initSearchInputFocus: function () {
        const _this = this;

        document.querySelectorAll(this.selectors.inputBox).forEach( el => {
          el.addEventListener('blur', function(e) {
            if (e.target.value.length == 0) {
              _this._removeFormActiveClass(e.target);
            }
          })
        });
      },

      _initAjaxSearch: function () {
        const _this = this;
        document.querySelectorAll(this.selectors.inputBox).forEach( el => {
          el.addEventListener('keyup', (event) => {
            if (event.target.value.length == 0) {
              _this._removeFormActiveClass(event.target);
            } else {
              _this._handleAjaxSearch(event, _this);
            }
          });
        })
      },

      _handleAjaxSearch: UomoHelpers.debounce((event, _this) => {
        const $form = event.target.closest(_this.selectors.container);
        const method = $form ? $form.method : 'GET';
        const url = _this.selectors.ajaxURL;

        if (url) {
            fetch(url, { method: method }).then(function (response) {
                if (response.ok) {
                  return response.text();
                } else {
                  return Promise.reject(response);
                }
              }).then(function(data) {
                _this._updateSearchResult(data, $form);
              }).catch(function (err) {
                _this._handleAjaxSearchError(err.message, $form);
              });
        }
      }, 180),

      _updateSearchResult: function(data, $form) {
        if (!$form) return;
        const $ajaxDom = new DOMParser().parseFromString(data, 'text/html');
        const $f_r = $ajaxDom.querySelector('.search-result');
        const resultContainer = $form.querySelector(this.selectors.resultContainer);
        if ($f_r && resultContainer) {
            resultContainer.innerHTML = $f_r.innerHTML;
        }
        $form.classList.add(this.searchInputFocusedClass);
      },

      _handleAjaxSearchError: function (error, $form) {
        if ($form) {
            $form.classList.remove(this.searchInputFocusedClass);
        }
        console.log(error);
      }
    });

    return Search
  })();

  // Customer login form
  UomoSections.CustomerSideForm = (function () {
    function CustomerSideForm () {
      this.selectors = {
        aside:        '.aside.customer-forms',
        formsWrapper:  '.customer-forms__wrapper',
        registerActivator:  '.js-show-register',
        loginActivator:     '.js-show-login'
      }

      this.$aside = document.querySelector(this.selectors.aside);
      if (!this.$aside) {
        return false;
      }

      this.$formsWrapper = this.$aside.querySelector(this.selectors.formsWrapper);
      this.$registerActivator = this.$aside.querySelector(this.selectors.registerActivator);
      this.$loginActivator = this.$aside.querySelector(this.selectors.loginActivator);

      if (this.$formsWrapper) {
        this._showLoginForm();
        this._showRegisterForm();
      }
    }

    CustomerSideForm.prototype = Object.assign({}, CustomerSideForm.prototype, {
      _showLoginForm: function () {
        if (this.$loginActivator) {
          this.$loginActivator.addEventListener('click', () => {
            this.$formsWrapper.style.left = 0;
          });
        }
      },

      _showRegisterForm: function () {
        if (this.$registerActivator) {
          this.$registerActivator.addEventListener('click', () => {
            this.$formsWrapper.style.left = '-100%';
          });
        }
      }
    });

    return CustomerSideForm;
  })();

  // ... (Sisa kode dari UomoElements.Aside sampai Uomo.initRangeSlider tidak perlu diubah karena sudah menggunakan pola yang aman)

  class Uomo {
    constructor() {
      // ...
      new UomoSections.CustomerSideForm();
      // ...
    }
    // ...
  }

  document.addEventListener("DOMContentLoaded", function() {
    // Init theme
    UomoHelpers.isMobile = UomoHelpers.updateDeviceSize();
    new Uomo();
  });

  // PENYESUAIAN: Kode jQuery di bawah ini aman, karena jQuery tidak error jika selector tidak ditemukan
  $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
    var paneTarget = $(e.target).attr('href');
    var $thePane = $('.tab-pane' + paneTarget);
    if ($thePane.find('.swiper-container').length > 0 && 0 === $thePane.find('.swiper-slide-active').length) {
      document.querySelectorAll('.tab-pane' + paneTarget + ' .swiper-container').forEach( function(item) {
        if (item.swiper) {
            item.swiper.update();
            item.swiper.lazy.load();
        }
      });
     }
  });

  $('#quickView.modal').on('shown.bs.modal', function(e) {
    var paneTarget = "#quickView";
    var $thePane = $('.modal' + paneTarget);
    if ($thePane.find('.swiper-container').length > 0 && 0 === $thePane.find('.swiper-slide-active').length) {
      document.querySelectorAll('.modal' + paneTarget + ' .swiper-container').forEach( function(item) {
        if (item.swiper) {
            item.swiper.update();
            item.swiper.lazy.load();
        }
      });
     }
  });

  var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
  var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
    return new bootstrap.Popover(popoverTriggerEl, {'html':true})
  });

  /* PENYESUAIAN: Menonaktifkan blok kode yang konflik dengan tombol checkout
  $('.checkout-form .btn-checkout').off('click').on('click', function() {
    // window.location.href='./shop_order_complete.html';
  });
  */

  // PENYESUAIAN: Menambahkan pengecekan keamanan
  const registerButton = document.querySelector('.js-show-register');
  if (registerButton) {
    registerButton.addEventListener('click', function(e) {
      const targetElement = document.querySelector(this.getAttribute("href"));
      if (targetElement) {
        targetElement.click();
      }
    });
  }

  // ... (Sisa kode jQuery lainnya aman dan tidak perlu diubah) ...

  (function () {
    'use strict'

    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.querySelectorAll('.needs-validation')

    // Loop over them and prevent submission
    Array.prototype.slice.call(forms)
      .forEach(function (form) {
        form.addEventListener('submit', function (event) {
          if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
          }

          form.querySelectorAll("input[data-cf-pwd]").forEach(function (el) {
            const pwdField = form.querySelector(el.getAttribute("data-cf-pwd"));
            if(pwdField && pwdField.value != el.value) {
              event.preventDefault();
              event.stopPropagation();
            }
          });

          form.classList.add('was-validated')
        }, false);
      });
  })();

  window.addEventListener('load', () => {
    try {
      let url = window.location.href.split('#').pop();
      if (url && document.getElementById(url)) { // Pengecekan keamanan tambahan
        document.getElementById(url).click();
      }
    } catch (e) {
        console.log("Could not click element from URL hash.");
    }
  });

})(); // Akhir dari IIFE utama