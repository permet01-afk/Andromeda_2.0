<?php

/* ucp_pm_history.html */
class __TwigTemplate_21af91bf2a9b22aedaef321f48aba492 extends Twig_Template
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
<h3 id=\"review\">
\t<span class=\"right-box\"><a href=\"#review\" onclick=\"viewableArea(getElementById('topicreview'), true); var rev_text = getElementById('review').getElementsByTagName('a').item(0).firstChild; if (rev_text.data == '";
        // line 3
        echo addslashes($this->env->getExtension('phpbb')->lang("EXPAND_VIEW"));
        echo "'){rev_text.data = '";
        echo addslashes($this->env->getExtension('phpbb')->lang("COLLAPSE_VIEW"));
        echo "'; } else if (rev_text.data == '";
        echo addslashes($this->env->getExtension('phpbb')->lang("COLLAPSE_VIEW"));
        echo "'){rev_text.data = '";
        echo addslashes($this->env->getExtension('phpbb')->lang("EXPAND_VIEW"));
        echo "'};\">";
        echo $this->env->getExtension('phpbb')->lang("EXPAND_VIEW");
        echo "</a></span>
\t";
        // line 4
        echo $this->env->getExtension('phpbb')->lang("MESSAGE_HISTORY");
        echo $this->env->getExtension('phpbb')->lang("COLON");
        echo "
</h3>

<div id=\"topicreview\">
\t<script type=\"text/javascript\">
\t// <![CDATA[
\t\tbbcodeEnabled = ";
        // line 10
        echo (isset($context["S_BBCODE_ALLOWED"]) ? $context["S_BBCODE_ALLOWED"] : null);
        echo ";
\t// ]]>
\t</script>
\t";
        // line 13
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "history_row"));
        foreach ($context['_seq'] as $context["_key"] => $context["history_row"]) {
            // line 14
            echo "\t<div class=\"post ";
            if (($this->getAttribute((isset($context["history_row"]) ? $context["history_row"] : null), "S_ROW_COUNT") % 2 == 0)) {
                echo "bg1";
            } else {
                echo "bg2";
            }
            echo "\">
\t\t<div class=\"inner\">

\t\t<div class=\"postbody\" id=\"pr";
            // line 17
            echo $this->getAttribute((isset($context["history_row"]) ? $context["history_row"] : null), "MSG_ID");
            echo "\">
\t\t\t<h3><a href=\"";
            // line 18
            echo $this->getAttribute((isset($context["history_row"]) ? $context["history_row"] : null), "U_VIEW_MESSAGE");
            echo "\" ";
            if ($this->getAttribute((isset($context["history_row"]) ? $context["history_row"] : null), "S_CURRENT_MSG")) {
                echo "class=\"current\"";
            }
            echo ">";
            echo $this->getAttribute((isset($context["history_row"]) ? $context["history_row"] : null), "SUBJECT");
            echo "</a></h3>

\t\t\t";
            // line 20
            if (($this->getAttribute((isset($context["history_row"]) ? $context["history_row"] : null), "U_QUOTE") || $this->getAttribute((isset($context["history_row"]) ? $context["history_row"] : null), "MESSAGE_AUTHOR_QUOTE"))) {
                // line 21
                echo "\t\t\t<ul class=\"post-buttons\">
\t\t\t\t<li>
\t\t\t\t\t<a ";
                // line 23
                if ($this->getAttribute((isset($context["history_row"]) ? $context["history_row"] : null), "U_QUOTE")) {
                    echo "href=\"";
                    echo $this->getAttribute((isset($context["history_row"]) ? $context["history_row"] : null), "U_QUOTE");
                    echo "\"";
                } else {
                    echo "href=\"#postingbox\" onclick=\"addquote(";
                    echo $this->getAttribute((isset($context["history_row"]) ? $context["history_row"] : null), "MSG_ID");
                    echo ", '";
                    echo $this->getAttribute((isset($context["history_row"]) ? $context["history_row"] : null), "MESSAGE_AUTHOR_QUOTE");
                    echo "', '";
                    echo addslashes($this->env->getExtension('phpbb')->lang("WROTE"));
                    echo "');\"";
                }
                echo " title=\"";
                echo $this->env->getExtension('phpbb')->lang("QUOTE");
                echo " ";
                echo $this->getAttribute((isset($context["history_row"]) ? $context["history_row"] : null), "MESSAGE_AUTHOR");
                echo "\" class=\"button icon-button quote-icon\">
\t\t\t\t\t\t<span>";
                // line 24
                echo $this->env->getExtension('phpbb')->lang("QUOTE");
                echo " ";
                echo $this->getAttribute((isset($context["history_row"]) ? $context["history_row"] : null), "MESSAGE_AUTHOR");
                echo "</span>
\t\t\t\t\t</a>
\t\t\t\t</li>
\t\t\t</ul>
\t\t\t";
            }
            // line 29
            echo "
\t\t\t<p class=\"author\">";
            // line 30
            echo $this->getAttribute((isset($context["history_row"]) ? $context["history_row"] : null), "MINI_POST_IMG");
            echo " ";
            echo $this->env->getExtension('phpbb')->lang("SENT_AT");
            echo $this->env->getExtension('phpbb')->lang("COLON");
            echo " <strong>";
            echo $this->getAttribute((isset($context["history_row"]) ? $context["history_row"] : null), "SENT_DATE");
            echo "</strong><br />
\t\t\t\t";
            // line 31
            echo $this->env->getExtension('phpbb')->lang("MESSAGE_BY_AUTHOR");
            echo " ";
            echo $this->getAttribute((isset($context["history_row"]) ? $context["history_row"] : null), "MESSAGE_AUTHOR_FULL");
            echo "</p>
\t\t\t<div class=\"content\">";
            // line 32
            if ($this->getAttribute((isset($context["history_row"]) ? $context["history_row"] : null), "MESSAGE")) {
                echo $this->getAttribute((isset($context["history_row"]) ? $context["history_row"] : null), "MESSAGE");
            } else {
                echo "<span class=\"error\">";
                echo $this->env->getExtension('phpbb')->lang("MESSAGE_REMOVED_FROM_OUTBOX");
                echo "</span>";
            }
            echo "</div>
\t\t\t<div id=\"message_";
            // line 33
            echo $this->getAttribute((isset($context["history_row"]) ? $context["history_row"] : null), "MSG_ID");
            echo "\" style=\"display: none;\">";
            echo $this->getAttribute((isset($context["history_row"]) ? $context["history_row"] : null), "DECODED_MESSAGE");
            echo "</div>
\t\t</div>

\t\t</div>
\t</div>
\t";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['history_row'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 39
        echo "</div>

<hr />
<p><a href=\"#cp-main\" class=\"top2\">";
        // line 42
        echo $this->env->getExtension('phpbb')->lang("BACK_TO_TOP");
        echo "</a></p>
";
    }

    public function getTemplateName()
    {
        return "ucp_pm_history.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  164 => 42,  159 => 39,  145 => 33,  135 => 32,  129 => 31,  120 => 30,  117 => 29,  107 => 24,  87 => 23,  83 => 21,  81 => 20,  70 => 18,  66 => 17,  55 => 14,  51 => 13,  45 => 10,  35 => 4,  23 => 3,  19 => 1,);
    }
}
