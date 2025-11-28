<?php

/* ucp_attachments.html */
class __TwigTemplate_f2f277a6d936ba020a3b7d2deae0848d extends Twig_Template
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
        $location = "ucp_header.html";
        $namespace = false;
        if (strpos($location, '@') === 0) {
            $namespace = substr($location, 1, strpos($location, '/') - 1);
            $previous_look_up_order = $this->env->getNamespaceLookUpOrder();
            $this->env->setNamespaceLookUpOrder(array($namespace, '__main__'));
        }
        $this->env->loadTemplate("ucp_header.html")->display($context);
        if ($namespace) {
            $this->env->setNamespaceLookUpOrder($previous_look_up_order);
        }
        // line 2
        echo "
<form id=\"ucp\" method=\"post\" action=\"";
        // line 3
        echo (isset($context["S_UCP_ACTION"]) ? $context["S_UCP_ACTION"] : null);
        echo "\"";
        echo (isset($context["S_FORM_ENCTYPE"]) ? $context["S_FORM_ENCTYPE"] : null);
        echo ">

<h2>";
        // line 5
        echo $this->env->getExtension('phpbb')->lang("TITLE");
        echo "</h2>

<div class=\"panel\">
\t<div class=\"inner\">
\t
\t<p>";
        // line 10
        echo $this->env->getExtension('phpbb')->lang("ATTACHMENTS_EXPLAIN");
        echo "</p>

\t";
        // line 12
        if (twig_length_filter($this->env, $this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "attachrow"))) {
            // line 13
            echo "\t\t<div class=\"action-bar top\">
\t\t\t<div class=\"pagination\">
\t\t\t\t";
            // line 15
            echo (isset($context["NUM_ATTACHMENTS"]) ? $context["NUM_ATTACHMENTS"] : null);
            echo "
\t\t\t\t";
            // line 16
            if (twig_length_filter($this->env, $this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "pagination"))) {
                echo " 
\t\t\t\t\t";
                // line 17
                $location = "pagination.html";
                $namespace = false;
                if (strpos($location, '@') === 0) {
                    $namespace = substr($location, 1, strpos($location, '/') - 1);
                    $previous_look_up_order = $this->env->getNamespaceLookUpOrder();
                    $this->env->setNamespaceLookUpOrder(array($namespace, '__main__'));
                }
                $this->env->loadTemplate("pagination.html")->display($context);
                if ($namespace) {
                    $this->env->setNamespaceLookUpOrder($previous_look_up_order);
                }
                // line 18
                echo "\t\t\t\t";
            } else {
                echo " 
\t\t\t\t\t &bull; ";
                // line 19
                echo (isset($context["PAGE_NUMBER"]) ? $context["PAGE_NUMBER"] : null);
                echo "
\t\t\t\t";
            }
            // line 21
            echo "\t\t\t</div>
\t\t</div>
\t\t
\t\t<ul class=\"topiclist\">
\t\t\t<li class=\"header\">
\t\t\t\t<dl>
\t\t\t\t\t<dt><div class=\"list-inner\"><a href=\"";
            // line 27
            echo (isset($context["U_SORT_FILENAME"]) ? $context["U_SORT_FILENAME"] : null);
            echo "\">";
            echo $this->env->getExtension('phpbb')->lang("FILENAME");
            echo "</a></div></dt>
\t\t\t\t\t<dd class=\"extra\"><a href=\"";
            // line 28
            echo (isset($context["U_SORT_DOWNLOADS"]) ? $context["U_SORT_DOWNLOADS"] : null);
            echo "\">";
            echo $this->env->getExtension('phpbb')->lang("DOWNLOADS");
            echo "</a></dd>
\t\t\t\t\t<dd class=\"time\"><span><a href=\"";
            // line 29
            echo (isset($context["U_SORT_POST_TIME"]) ? $context["U_SORT_POST_TIME"] : null);
            echo "\">";
            echo $this->env->getExtension('phpbb')->lang("POST_TIME");
            echo "</a></span></dd>
\t\t\t\t\t<dd class=\"mark\">";
            // line 30
            echo $this->env->getExtension('phpbb')->lang("MARK");
            echo "</dd>
\t\t\t\t</dl>
\t\t\t</li>
\t\t</ul>
\t\t<ul class=\"topiclist cplist responsive-show-columns\">

\t\t";
            // line 36
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "attachrow"));
            foreach ($context['_seq'] as $context["_key"] => $context["attachrow"]) {
                // line 37
                echo "\t\t<li class=\"row";
                if (($this->getAttribute((isset($context["attachrow"]) ? $context["attachrow"] : null), "S_ROW_COUNT") % 2 == 1)) {
                    echo " bg1";
                } else {
                    echo " bg2";
                }
                echo "\">
\t\t\t<dl>
\t\t\t\t<dt>
\t\t\t\t\t<div class=\"list-inner\">
\t\t\t\t\t\t<a href=\"";
                // line 41
                echo $this->getAttribute((isset($context["attachrow"]) ? $context["attachrow"] : null), "U_VIEW_ATTACHMENT");
                echo "\" class=\"topictitle attachment-filename ellipsis-text\" title=\"";
                echo $this->getAttribute((isset($context["attachrow"]) ? $context["attachrow"] : null), "FILENAME");
                echo "\">";
                echo $this->getAttribute((isset($context["attachrow"]) ? $context["attachrow"] : null), "FILENAME");
                echo "</a> (";
                echo $this->getAttribute((isset($context["attachrow"]) ? $context["attachrow"] : null), "SIZE");
                echo ")<br />
\t\t\t\t\t\t";
                // line 42
                if ($this->getAttribute((isset($context["attachrow"]) ? $context["attachrow"] : null), "S_IN_MESSAGE")) {
                    echo $this->env->getExtension('phpbb')->lang("PM");
                    echo $this->env->getExtension('phpbb')->lang("COLON");
                    echo " ";
                } else {
                    echo $this->env->getExtension('phpbb')->lang("TOPIC");
                    echo $this->env->getExtension('phpbb')->lang("COLON");
                    echo " ";
                }
                echo "<a href=\"";
                echo $this->getAttribute((isset($context["attachrow"]) ? $context["attachrow"] : null), "U_VIEW_TOPIC");
                echo "\">";
                echo $this->getAttribute((isset($context["attachrow"]) ? $context["attachrow"] : null), "TOPIC_TITLE");
                echo "</a>
\t\t\t\t\t</div>
\t\t\t\t</dt>
\t\t\t\t<dd class=\"extra\">";
                // line 45
                echo $this->getAttribute((isset($context["attachrow"]) ? $context["attachrow"] : null), "DOWNLOAD_COUNT");
                echo "</dd>
\t\t\t\t<dd class=\"time\"><span>";
                // line 46
                echo $this->getAttribute((isset($context["attachrow"]) ? $context["attachrow"] : null), "POST_TIME");
                echo "</span></dd>
\t\t\t\t<dd class=\"mark\"><input type=\"checkbox\" name=\"attachment[";
                // line 47
                echo $this->getAttribute((isset($context["attachrow"]) ? $context["attachrow"] : null), "ATTACH_ID");
                echo "]\" value=\"1\" /></dd>
\t\t\t</dl>
\t\t</li>
\t\t";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['attachrow'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 51
            echo "\t\t</ul>

\t\t<fieldset class=\"display-options\">
\t\t\t<label for=\"sk\">";
            // line 54
            echo $this->env->getExtension('phpbb')->lang("SORT_BY");
            echo $this->env->getExtension('phpbb')->lang("COLON");
            echo " <select name=\"sk\" id=\"sk\">";
            echo (isset($context["S_SORT_OPTIONS"]) ? $context["S_SORT_OPTIONS"] : null);
            echo "</select></label> 
\t\t\t<label><select name=\"sd\" id=\"sd\">";
            // line 55
            echo (isset($context["S_ORDER_SELECT"]) ? $context["S_ORDER_SELECT"] : null);
            echo "</select></label>
\t\t\t<input class=\"button2\" type=\"submit\" name=\"sort\" value=\"";
            // line 56
            echo $this->env->getExtension('phpbb')->lang("SORT");
            echo "\" />
\t\t\t";
            // line 57
            echo (isset($context["S_FORM_TOKEN"]) ? $context["S_FORM_TOKEN"] : null);
            echo "
\t\t</fieldset>

\t\t<hr />

\t\t<div class=\"action-bar bottom\">
\t\t\t<div class=\"pagination\">
\t\t\t\t";
            // line 64
            echo (isset($context["TOTAL_ATTACHMENTS"]) ? $context["TOTAL_ATTACHMENTS"] : null);
            echo " ";
            echo $this->env->getExtension('phpbb')->lang("TITLE");
            echo "
\t\t\t\t";
            // line 65
            if (twig_length_filter($this->env, $this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "pagination"))) {
                echo " 
\t\t\t\t\t";
                // line 66
                $location = "pagination.html";
                $namespace = false;
                if (strpos($location, '@') === 0) {
                    $namespace = substr($location, 1, strpos($location, '/') - 1);
                    $previous_look_up_order = $this->env->getNamespaceLookUpOrder();
                    $this->env->setNamespaceLookUpOrder(array($namespace, '__main__'));
                }
                $this->env->loadTemplate("pagination.html")->display($context);
                if ($namespace) {
                    $this->env->setNamespaceLookUpOrder($previous_look_up_order);
                }
                // line 67
                echo "\t\t\t\t";
            } else {
                echo " 
\t\t\t\t\t &bull; ";
                // line 68
                echo (isset($context["PAGE_NUMBER"]) ? $context["PAGE_NUMBER"] : null);
                echo "
\t\t\t\t";
            }
            // line 70
            echo "\t\t\t</div>
\t\t</div>

\t";
        } else {
            // line 74
            echo "\t\t<p><strong>";
            echo $this->env->getExtension('phpbb')->lang("UCP_NO_ATTACHMENTS");
            echo "</strong></p>
\t";
        }
        // line 76
        echo "
\t</div>
</div>
\t
";
        // line 80
        if ((isset($context["S_ATTACHMENT_ROWS"]) ? $context["S_ATTACHMENT_ROWS"] : null)) {
            // line 81
            echo "\t<fieldset class=\"display-actions\">\t
\t\t<input class=\"button2\" type=\"submit\" name=\"delete\" value=\"";
            // line 82
            echo $this->env->getExtension('phpbb')->lang("DELETE_MARKED");
            echo "\" />
\t\t<div><a href=\"#\" onclick=\"marklist('ucp', 'attachment', true); return false;\">";
            // line 83
            echo $this->env->getExtension('phpbb')->lang("MARK_ALL");
            echo "</a> &bull; <a href=\"#\" onclick=\"marklist('ucp', 'attachment', false); return false;\">";
            echo $this->env->getExtension('phpbb')->lang("UNMARK_ALL");
            echo "</a></div>
\t\t";
            // line 84
            echo (isset($context["S_FORM_TOKEN"]) ? $context["S_FORM_TOKEN"] : null);
            echo "
\t</fieldset>
";
        }
        // line 87
        echo "</form>

";
        // line 89
        $location = "ucp_footer.html";
        $namespace = false;
        if (strpos($location, '@') === 0) {
            $namespace = substr($location, 1, strpos($location, '/') - 1);
            $previous_look_up_order = $this->env->getNamespaceLookUpOrder();
            $this->env->setNamespaceLookUpOrder(array($namespace, '__main__'));
        }
        $this->env->loadTemplate("ucp_footer.html")->display($context);
        if ($namespace) {
            $this->env->setNamespaceLookUpOrder($previous_look_up_order);
        }
    }

    public function getTemplateName()
    {
        return "ucp_attachments.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  292 => 89,  288 => 87,  282 => 84,  276 => 83,  272 => 82,  269 => 81,  267 => 80,  261 => 76,  255 => 74,  249 => 70,  244 => 68,  239 => 67,  227 => 66,  223 => 65,  217 => 64,  207 => 57,  203 => 56,  199 => 55,  192 => 54,  187 => 51,  177 => 47,  173 => 46,  169 => 45,  151 => 42,  141 => 41,  129 => 37,  125 => 36,  116 => 30,  110 => 29,  104 => 28,  98 => 27,  90 => 21,  85 => 19,  80 => 18,  68 => 17,  64 => 16,  60 => 15,  56 => 13,  54 => 12,  49 => 10,  41 => 5,  34 => 3,  31 => 2,  19 => 1,);
    }
}
