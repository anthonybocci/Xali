<?php

namespace Xali\Bundle\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class XaliUserBundle extends Bundle
{
    public function getParent() {
        return "FOSUserBundle";
    }
}
