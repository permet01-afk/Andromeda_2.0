<?php

/* newtopic_notify.txt */
class __TwigTemplate_c100cd5d415626a4d67eceae29e4f1e0 extends Twig_Template
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
        echo "Subject: New topic notification - \"";
        echo (isset($context["FORUM_NAME"]) ? $context["FORUM_NAME"] : null);
        echo "\"

Hello ";
        // line 3
        echo (isset($context["USERNAME"]) ? $context["USERNAME"] : null);
        echo ",

You are receiving this notification because you are watching the forum \"";
        // line 5
        echo (isset($context["FORUM_NAME"]) ? $context["FORUM_NAME"] : null);
        echo "\" at \"";
        echo (isset($context["SITENAME"]) ? $context["SITENAME"] : null);
        echo "\". This forum has received a new topic";
        if (((isset($context["AUTHOR_NAME"]) ? $context["AUTHOR_NAME"] : null) !== "")) {
            echo " by ";
            echo (isset($context["AUTHOR_NAME"]) ? $context["AUTHOR_NAME"] : null);
        }
        echo " since your last visit, \"";
        echo (isset($context["TOPIC_TITLE"]) ? $context["TOPIC_TITLE"] : null);
        echo "\". You can use the following link to view the forum, no more notifications will be sent until you visit the forum.

";
        // line 7
        echo (isset($context["U_FORUM"]) ? $context["U_FORUM"] : null);
        echo "

If you no longer wish to watch this forum you can either click the \"Unsubscribe forum\" link found in the forum above, or by clicking the following link:

";
        // line 11
        echo (isset($context["U_STOP_WATCHING_FORUM"]) ? $context["U_STOP_WATCHING_FORUM"] : null);
        echo "

";
        // line 13
        echo (isset($context["EMAIL_SIG"]) ? $context["EMAIL_SIG"] : null);
        echo "
";
    }

    public function getTemplateName()
    {
        return "newtopic_notify.txt";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  56 => 13,  51 => 11,  44 => 7,  30 => 5,  25 => 3,  19 => 1,);
    }
}
