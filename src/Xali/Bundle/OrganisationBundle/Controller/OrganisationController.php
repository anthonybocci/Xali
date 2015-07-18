<?php

namespace Xali\Bundle\OrganisationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class OrganisationController extends Controller
{
    public function add_organisationAction()
    {
        return $this->render('XaliOrganisationBundle:Management:add_organisation.html.twig');
    }
    
    public function update_organisationAction()
    {
        return $this->render('XaliOrganisationBundle:Management:update_organisation.html.twig');
    }
    
    public function profileAction() {
        return $this->render('XaliOrganisationBundle:Profile:profile.html.twig');
    }
}
