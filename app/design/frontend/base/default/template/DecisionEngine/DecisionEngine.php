<?php

if ($this->isActive()) {
    $widgetUrl = $this->getWidgetUrl();
    print <<<EOD
<script>
    (function (s, n) {
        s = document.createElement('script');
        s.async = 1;
        s.src = '{$widgetUrl}';
        n = document.getElementsByTagName('script')[0];
        n.parentNode.insertBefore(s, n);
    })();
</script>
EOD;
}
