<?php

/* drafts.html */
class __TwigTemplate_b90ac6932dc6c78e2e750556ed4aa41f extends Twig_Template
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

";
        // line 3
        if (twig_length_filter($this->env, $this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "draftrow"))) {
            // line 4
            echo "<div class=\"panel\">
\t<div class=\"inner\">

\t<h3 class=\"draft-title\">";
            // line 7
            echo $this->env->getExtension('phpbb')->lang("LOAD_DRAFT");
            echo "</h3>
\t<p>";
            // line 8
            echo $this->env->getExtension('phpbb')->lang("LOAD_DRAFT_EXPLAIN");
            echo "</p>

\t</div>
</div>

<div class=\"";
            // line 13
            if ((!(isset($context["S_PRIVMSGS"]) ? $context["S_PRIVMSGS"] : null))) {
                echo "forumbg";
            } else {
                echo "panel";
            }
            echo "\">
\t<div class=\"inner\">

\t<ul class=\"topiclist two-long-columns\">
\t\t<li class=\"header\">
\t\t\t<dl>
\t\t\t\t<dt>";
            // line 19
            echo $this->env->getExtension('phpbb')->lang("LOAD_DRAFT");
            echo "</dt>
\t\t\t\t<dd class=\"info\">";
            // line 20
            echo $this->env->getExtension('phpbb')->lang("SAVE_DATE");
            echo "</dd>
\t\t\t</dl>
\t\t</li>
\t</ul>
\t<ul class=\"topiclist two-long-columns";
            // line 24
            if ((!(isset($context["S_PRIVMSGS"]) ? $context["S_PRIVMSGS"] : null))) {
                echo " topics";
            } else {
                echo " cplist";
            }
            echo "\">

\t";
            // line 26
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "draftrow"));
            foreach ($context['_seq'] as $context["_key"] => $context["draftrow"]) {
                // line 27
                echo "\t<li class=\"row";
                if (($this->getAttribute((isset($context["draftrow"]) ? $context["draftrow"] : null), "S_ROW_COUNT") % 2 == 0)) {
                    echo " bg1";
                } else {
                    echo " bg2";
                }
                echo "\">
\t\t<dl>
\t\t\t<dt>
\t\t\t\t<div class=\"list-inner\">
\t\t\t\t\t<a href=\"";
                // line 31
                echo $this->getAttribute((isset($context["draftrow"]) ? $context["draftrow"] : null), "U_INSERT");
                echo "\" title=\"";
                echo $this->env->getExtension('phpbb')->lang("LOAD_DRAFT");
                echo "\" class=\"topictitle\">";
                echo $this->getAttribute((isset($context["draftrow"]) ? $context["draftrow"] : null), "DRAFT_SUBJECT");
                echo "</a><br />
\t\t\t\t\t";
                // line 32
                if ((!(isset($context["S_PRIVMSGS"]) ? $context["S_PRIVMSGS"] : null))) {
                    if ($this->getAttribute((isset($context["draftrow"]) ? $context["draftrow"] : null), "S_LINK_TOPIC")) {
                        echo $this->env->getExtension('phpbb')->lang("TOPIC");
                        echo $this->env->getExtension('phpbb')->lang("COLON");
                        echo " <a href=\"";
                        echo $this->getAttribute((isset($context["draftrow"]) ? $context["draftrow"] : null), "U_VIEW");
                        echo "\">";
                        echo $this->getAttribute((isset($context["draftrow"]) ? $context["draftrow"] : null), "TITLE");
                        echo "</a>
\t\t\t\t\t";
                    } elseif ($this->getAttribute((isset($context["draftrow"]) ? $context["draftrow"] : null), "S_LINK_FORUM")) {
                        // line 33
                        echo $this->env->getExtension('phpbb')->lang("FORUM");
                        echo $this->env->getExtension('phpbb')->lang("COLON");
                        echo " <a href=\"";
                        echo $this->getAttribute((isset($context["draftrow"]) ? $context["draftrow"] : null), "U_VIEW");
                        echo "\">";
                        echo $this->getAttribute((isset($context["draftrow"]) ? $context["draftrow"] : null), "TITLE");
                        echo "</a>
\t\t\t\t\t";
                    } else {
                        // line 34
                        echo $this->env->getExtension('phpbb')->lang("NO_TOPIC_FORUM");
                    }
                }
                // line 35
                echo "\t\t\t\t\t<div class=\"responsive-show\" style=\"display: none;\">
\t\t\t\t\t\t";
                // line 36
                echo $this->env->getExtension('phpbb')->lang("SAVE_DATE");
                echo $this->env->getExtension('phpbb')->lang("COLON");
                echo " <strong>";
                echo $this->getAttribute((isset($context["draftrow"]) ? $context["draftrow"] : null), "DATE");
                echo "</strong>
\t\t\t\t\t</div>
\t\t\t\t</div>
\t\t\t</dt>
\t\t\t<dd class=\"info\"><span>";
                // line 40
                echo $this->getAttribute((isset($context["draftrow"]) ? $context["draftrow"] : null), "DATE");
                echo "</span></dd>
\t\t</dl>
\t</li>
\t";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['draftrow'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 44
            echo "
\t</ul>

\t</div>
</div>
";
        }
    }

    public function getTemplateName()
    {
        return "drafts.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  148 => 44,  138 => 40,  128 => 36,  125 => 35,  121 => 34,  111 => 33,  99 => 32,  91 => 31,  79 => 27,  75 => 26,  66 => 24,  59 => 20,  55 => 19,  42 => 13,  34 => 8,  30 => 7,  25 => 4,  23 => 3,  19 => 1,);
    }
}
