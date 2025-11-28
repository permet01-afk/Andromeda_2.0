<?php

/* contact_admin.txt */
class __TwigTemplate_6494230826c24ca0f95c3095462ba8d9 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "
Hello ";
        // line 2
        echo (isset($context["TO_USERNAME"]) ? $context["TO_USERNAME"] : null);
        echo ",

The following is an e-mail sent to you through the administration contact page on \"";
        // line 4
        echo (isset($context["SITENAME"]) ? $context["SITENAME"] : null);
        echo "\".

";
        // line 6
        if ((isset($context["S_IS_REGISTERED"]) ? $context["S_IS_REGISTERED"] : null)) {
            // line 7
            echo "The message has been sent from an account on the site.
Username: ";
            // line 8
            echo (isset($context["FROM_USERNAME"]) ? $context["FROM_USERNAME"] : null);
            echo "
E-mail address: ";
            // line 9
            echo (isset($context["FROM_EMAIL_ADDRESS"]) ? $context["FROM_EMAIL_ADDRESS"] : null);
            echo "
IP Address: ";
            // line 10
            echo (isset($context["FROM_IP_ADDRESS"]) ? $context["FROM_IP_ADDRESS"] : null);
            echo "
Profile: ";
            // line 11
            echo (isset($context["U_FROM_PROFILE"]) ? $context["U_FROM_PROFILE"] : null);
            echo "
";
        } else {
            // line 13
            echo "The message was sent from a guest who specified the following contact information:
Name: ";
            // line 14
            echo (isset($context["FROM_USERNAME"]) ? $context["FROM_USERNAME"] : null);
            echo "
E-mail address: ";
            // line 15
            echo (isset($context["FROM_EMAIL_ADDRESS"]) ? $context["FROM_EMAIL_ADDRESS"] : null);
            echo "
IP Address: ";
            // line 16
            echo (isset($context["FROM_IP_ADDRESS"]) ? $context["FROM_IP_ADDRESS"] : null);
            echo "
";
        }
        // line 18
        echo "

Message sent to you follows
~~~~~~~~~~~~~~~~~~~~~~~~~~~

";
        // line 23
        echo (isset($context["MESSAGE"]) ? $context["MESSAGE"] : null);
        echo "
";
    }

    public function getTemplateName()
    {
        return "contact_admin.txt";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  77 => 23,  70 => 18,  65 => 16,  61 => 15,  57 => 14,  54 => 13,  49 => 11,  45 => 10,  41 => 9,  37 => 8,  34 => 7,  32 => 6,  27 => 4,  22 => 2,  19 => 1,);
    }
}
