<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>	 
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" ></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>
<script src="https://www.jqueryscript.net/demo/Lightweight-jQuery-Color-Picker-Plugin-For-Bootstrap-Colorselector/dist/bootstrap-colorselector.min.js"></script>

<!--script src="js/dragdrop.js"></script-->
    <script>    

    var mobile = 'false',
      isTestPage = false,
      isDemoPage = true,
      classIn = 'jello',
      classOut = 'rollOut',
      speed = 400,
      doc = document,
      win = window,
      ww = win.innerWidth || doc.documentElement.clientWidth || doc.body.clientWidth,
      fw = getFW(ww),
      initFns = {},
      sliders = new Object(),
      edgepadding = 50,
      gutter = 10;

    function getFW (width) {
    var sm = 400, md = 900, lg = 1400;
    return width < sm ? 150 : width >= sm && width < md ? 200 : width >= md && width < lg ? 300 : 400;
    }
    window.addEventListener('resize', function() { fw = getFW(ww); });
    </script>
    <script src="js/tiny-slider.js"></script>
    <script>

    // <script type="module">
    // import { tns } from '../src/tiny-slider.js';

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
	//let $=jQuery;
</script>