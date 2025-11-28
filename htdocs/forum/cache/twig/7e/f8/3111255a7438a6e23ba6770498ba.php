<?php

/* posting_review.html */
class __TwigTemplate_7ef83111255a7438a6e23ba6770498ba extends Twig_Template
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
        echo "<h3>";
        echo $this->env->getExtension('phpbb')->lang("POST_REVIEW");
        echo "</h3>

<p>";
        // line 3
        echo $this->env->getExtension('phpbb')->lang("POST_REVIEW_EXPLAIN");
        echo "</p>

";
        // line 5
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "post_review_row"));
        foreach ($context['_seq'] as $context["_key"] => $context["post_review_row"]) {
            // line 6
            if ($this->getAttribute((isset($context["post_review_row"]) ? $context["post_review_row"] : null), "S_IGNORE_POST")) {
                // line 7
                echo "<div class=\"post bg3 post-ignore\">
\t<div class=\"inner\">
\t\t";
                // line 9
                echo $this->getAttribute((isset($context["post_review_row"]) ? $context["post_review_row"] : null), "L_IGNORE_POST");
                echo "
";
            } else {
                // line 11
                echo "<div class=\"post ";
                if (($this->getAttribute((isset($context["post_review_row"]) ? $context["post_review_row"] : null), "S_ROW_COUNT") % 2 == 1)) {
                    echo "bg1";
                } else {
                    echo "bg2";
                }
                echo "\">
\t<div class=\"inner\">
";
            }
            // line 14
            echo "
\t<div class=\"postbody\" id=\"ppr";
            // line 15
            echo $this->getAttribute((isset($context["post_review_row"]) ? $context["post_review_row"] : null), "POST_ID");
            echo "\">
\t\t<h3><a href=\"#ppr";
            // line 16
            echo $this->getAttribute((isset($context["post_review_row"]) ? $context["post_review_row"] : null), "POST_ID");
            echo "\">";
            echo $this->getAttribute((isset($context["post_review_row"]) ? $context["post_review_row"] : null), "POST_SUBJECT");
            echo "</a></h3>
\t\t<p class=\"author\">";
            // line 17
            if ((isset($context["S_IS_BOT"]) ? $context["S_IS_BOT"] : null)) {
                echo $this->getAttribute((isset($context["post_review_row"]) ? $context["post_review_row"] : null), "MINI_POST_IMG");
            } else {
                echo "<a href=\"";
                echo $this->getAttribute((isset($context["post_review_row"]) ? $context["post_review_row"] : null), "U_MINI_POST");
                echo "\">";
                echo $this->getAttribute((isset($context["post_review_row"]) ? $context["post_review_row"] : null), "MINI_POST_IMG");
                echo "</a>";
            }
            echo " ";
            echo $this->env->getExtension('phpbb')->lang("POST_BY_AUTHOR");
            echo "<strong>  ";
            echo $this->getAttribute((isset($context["post_review_row"]) ? $context["post_review_row"] : null), "POST_AUTHOR_FULL");
            echo "</strong> &raquo; ";
            echo $this->getAttribute((isset($context["post_review_row"]) ? $context["post_review_row"] : null), "POST_DATE");
            echo "</p>
\t\t<div class=\"content\">";
            // line 18
            echo $this->getAttribute((isset($context["post_review_row"]) ? $context["post_review_row"] : null), "MESSAGE");
            echo "</div>

\t\t";
            // line 20
            if ($this->getAttribute((isset($context["post_review_row"]) ? $context["post_review_row"] : null), "S_HAS_ATTACHMENTS")) {
                // line 21
                echo "\t\t\t<dl class=\"attachbox\">
\t\t\t\t<dt>";
                // line 22
                echo $this->env->getExtension('phpbb')->lang("ATTACHMENTS");
                echo "</dt>
\t\t\t\t";
                // line 23
                $context['_parent'] = (array) $context;
                $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["post_review_row"]) ? $context["post_review_row"] : null), "attachment"));
                foreach ($context['_seq'] as $context["_key"] => $context["attachment"]) {
                    // line 24
                    echo "\t\t\t\t\t<dd>";
                    echo $this->getAttribute((isset($context["attachment"]) ? $context["attachment"] : null), "DISPLAY_ATTACHMENT");
                    echo "</dd>
\t\t\t\t";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['attachment'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 26
                echo "\t\t\t</dl>
\t\t";
            }
            // line 28
            echo "
\t</div>

\t</div>
</div>
";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['post_review_row'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 34
        echo "
<hr />
";
    }

    public function getTemplateName()
    {
        return "posting_review.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  129 => 34,  118 => 28,  114 => 26,  105 => 24,  101 => 23,  97 => 22,  94 => 21,  92 => 20,  87 => 18,  69 => 17,  63 => 16,  59 => 15,  56 => 14,  45 => 11,  40 => 9,  36 => 7,  34 => 6,  30 => 5,  25 => 3,  19 => 1,);
    }
}
