
    var options = {
    
        'continue-loop': {
            container: '',
            mouseDrag: true,
          // items: 3,
          loop: false,
          responsive: {
            300: {
              items: 1.6
            },
            500: {
              items: 4.8
            }
          },
        },
        'live-loop': {
          container: '',
            mouseDrag: true,
          loop: false,
          responsive: {
            300: {
              items: 4.5
            },
            500: {
              items: 9.8
            }
          },
        },
        'session-loop': {
          container: '',
            mouseDrag: true,
          loop: false,
          responsive: {
            300: {
              items: 1.6
            },
            500: {
              items: 4.8
            }
          },
        },
        'book-loop': {
          container: '',
            mouseDrag: true,
          loop: false,
          responsive: {
            300: {
              items: 2.7
            },
            500: {
              items: 7.8
            }
          },
        },
      };
    
        for (var i in options) {
        var item = options[i];
        item.container = '#' + i;
        item.swipeAngle = false;
        if (!item.speed) { item.speed = speed; }
    
        if (doc.querySelector(item.container)) {
          sliders[i] = tns(options[i]);
    
        } else if (i.indexOf('responsive') >= 0) {
          if (isTestPage && initFns[i]) { initFns[i](); }
        }
        }
    
        // goto
       
        </script>
        <script>
         if (location.hash) {
      $('a[href=\'' + location.hash + '\']').tab('show');
    }
    var activeTab = localStorage.getItem('activeTab');
    if (activeTab) {
      $('a[href="' + activeTab + '"]').tab('show');
    }
    
    $('body').on('click', 'a[data-toggle=\'tab\']', function (e) {
      e.preventDefault()
      var tab_name = this.getAttribute('href')
      if (history.pushState) {
        history.pushState(null, null, tab_name)
      }
      else {
        location.hash = tab_name
      }
      localStorage.setItem('activeTab', tab_name)
    
      $(this).tab('show');
      return false;
    });
    $(window).on('popstate', function () {
      var anchor = location.hash ||
        $('a[data-toggle=\'tab\']').first().attr('href');
      $('a[href=\'' + anchor + '\']').tab('show');
    });
    
        </script>
        <script>
    jQuery(function($) {
         var path = window.location.href; // because the 'href' property of the DOM element is the absolute path
         $('.nav-act a').each(function() {
          if (this.href === path) {
           $(this).addClass('active');
          }
         });
        });