<?php

if ($this->isActive()) {
    $src = json_encode($this->getWidgetUrl());
    print "<script>(function(d,t,s,f){s=d.createElement(t);s.async=1;s.src={$src};f=d.getElementsByTagName(t)[0];f.parentNode.insertBefore(s,f)})(document,'script')</script>";
}
