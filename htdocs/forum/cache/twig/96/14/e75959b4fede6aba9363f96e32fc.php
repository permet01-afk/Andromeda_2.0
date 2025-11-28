<?php

/* report_body.html */
class __TwigTemplate_9614e75959b4fede6aba9363f96e32fc extends Twig_Template
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
        $location = "overall_header.html";
        $namespace = false;
        if (strpos($location, '@') === 0) {
            $namespace = substr($location, 1, strpos($location, '/') - 1);
            $previous_look_up_order = $this->env->getNamespaceLookUpOrder();
            $this->env->setNamespaceLookUpOrder(array($namespace, '__main__'));
        }
        $this->env->loadTemplate("overall_header.html")->display($context);
        if ($namespace) {
            $this->env->setNamespaceLookUpOrder($previous_look_up_order);
        }
        // line 2
        echo "
<h2 class=\"titlespace\">";
        // line 3
        if ((isset($context["S_REPORT_POST"]) ? $context["S_REPORT_POST"] : null)) {
            echo $this->env->getExtension('phpbb')->lang("REPORT_POST");
        } else {
            echo $this->env->getExtension('phpbb')->lang("REPORT_MESSAGE");
        }
        echo "</h2>

<form method=\"post\" action=\"";
        // line 5
        echo (isset($context["S_REPORT_ACTION"]) ? $context["S_REPORT_ACTION"] : null);
        echo "\" id=\"report\">
<div class=\"panel\">
\t<div class=\"inner\">

\t<div class=\"content\">
\t\t<p>";
        // line 10
        if ((isset($context["S_REPORT_POST"]) ? $context["S_REPORT_POST"] : null)) {
            echo $this->env->getExtension('phpbb')->lang("REPORT_POST_EXPLAIN");
        } else {
            echo $this->env->getExtension('phpbb')->lang("REPORT_MESSAGE_EXPLAIN");
        }
        echo "</p>
\t\t
\t\t<fieldset>
\t\t";
        // line 13
        if ((isset($context["ERROR"]) ? $context["ERROR"] : null)) {
            echo "<dl><dd class=\"error\">";
            echo (isset($context["ERROR"]) ? $context["ERROR"] : null);
            echo "</dd></dl>";
        }
        // line 14
        echo "\t\t<dl class=\"fields2\">
\t\t\t<dt><label for=\"reason_id\">";
        // line 15
        echo $this->env->getExtension('phpbb')->lang("REASON");
        echo $this->env->getExtension('phpbb')->lang("COLON");
        echo "</label></dt>
\t\t\t<dd><select name=\"reason_id\" id=\"reason_id\" class=\"full\">";
        // line 16
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "reason"));
        foreach ($context['_seq'] as $context["_key"] => $context["reason"]) {
            echo "<option value=\"";
            echo $this->getAttribute((isset($context["reason"]) ? $context["reason"] : null), "ID");
            echo "\"";
            if ($this->getAttribute((isset($context["reason"]) ? $context["reason"] : null), "S_SELECTED")) {
                echo " selected=\"selected\"";
            }
            echo ">";
            echo $this->getAttribute((isset($context["reason"]) ? $context["reason"] : null), "DESCRIPTION");
            echo "</option>";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['reason'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        echo "</select></dd>
\t\t</dl>
\t\t";
        // line 18
        if ((isset($context["S_CAN_NOTIFY"]) ? $context["S_CAN_NOTIFY"] : null)) {
            // line 19
            echo "\t\t\t<dl class=\"fields2\">
\t\t\t\t<dt><label for=\"notify1\">";
            // line 20
            echo $this->env->getExtension('phpbb')->lang("REPORT_NOTIFY");
            echo $this->env->getExtension('phpbb')->lang("COLON");
            echo "</label><br /><span>";
            echo $this->env->getExtension('phpbb')->lang("REPORT_NOTIFY_EXPLAIN");
            echo "</span></dt>
\t\t\t\t<dd>
\t\t\t\t\t<label for=\"notify1\"><input type=\"radio\" name=\"notify\" id=\"notify1\" value=\"1\" ";
            // line 22
            if ((!(isset($context["S_NOTIFY"]) ? $context["S_NOTIFY"] : null))) {
                echo "checked=\"checked\"";
            }
            echo " /> ";
            echo $this->env->getExtension('phpbb')->lang("YES");
            echo "</label> 
\t\t\t\t\t<label for=\"notify0\"><input type=\"radio\" name=\"notify\" id=\"notify0\" value=\"0\" ";
            // line 23
            if ((isset($context["S_NOTIFY"]) ? $context["S_NOTIFY"] : null)) {
                echo "checked=\"checked\"";
            }
            echo " /> ";
            echo $this->env->getExtension('phpbb')->lang("NO");
            echo "</label>
\t\t\t\t</dd>
\t\t\t</dl>
\t\t";
        }
        // line 27
        echo "\t\t<dl class=\"fields2\">
\t\t\t<dt><label for=\"report_text\">";
        // line 28
        echo $this->env->getExtension('phpbb')->lang("MORE_INFO");
        echo $this->env->getExtension('phpbb')->lang("COLON");
        echo "</label><br /><span>";
        echo $this->env->getExtension('phpbb')->lang("CAN_LEAVE_BLANK");
        echo "</span></dt>
\t\t\t<dd><textarea name=\"report_text\" id=\"report_text\" rows=\"10\" cols=\"76\" class=\"inputbox\">";
        // line 29
        echo (isset($context["REPORT_TEXT"]) ? $context["REPORT_TEXT"] : null);
        echo "</textarea></dd>
\t\t</dl>
\t\t";
        // line 31
        if ((isset($context["CAPTCHA_TEMPLATE"]) ? $context["CAPTCHA_TEMPLATE"] : null)) {
            // line 32
            echo "\t\t\t";
            $location = (("" . (isset($context["CAPTCHA_TEMPLATE"]) ? $context["CAPTCHA_TEMPLATE"] : null)) . "");
            $namespace = false;
            if (strpos($location, '@') === 0) {
                $namespace = substr($location, 1, strpos($location, '/') - 1);
                $previous_look_up_order = $this->env->getNamespaceLookUpOrder();
                $this->env->setNamespaceLookUpOrder(array($namespace, '__main__'));
            }
            $template = $this->env->resolveTemplate((("" . (isset($context["CAPTCHA_TEMPLATE"]) ? $context["CAPTCHA_TEMPLATE"] : null)) . ""));
            $template->display($context);
            if ($namespace) {
                $this->env->setNamespaceLookUpOrder($previous_look_up_order);
            }
            // line 33
            echo "\t\t";
        }
        // line 34
        echo "\t\t</fieldset>
\t</div>

\t</div>
</div>

<div class=\"panel\">
\t<div class=\"inner\">

\t<div class=\"content\">
\t\t<fieldset class=\"submit-buttons\">
\t\t\t<input type=\"submit\" name=\"submit\" class=\"button1\" value=\"";
        // line 45
        echo $this->env->getExtension('phpbb')->lang("SUBMIT");
        echo "\" />&nbsp;
\t\t\t<input type=\"submit\" name=\"cancel\" class=\"button2\" value=\"";
        // line 46
        echo $this->env->getExtension('phpbb')->lang("CANCEL");
        echo "\" />
\t\t\t";
        // line 47
        echo (isset($context["S_FORM_TOKEN"]) ? $context["S_FORM_TOKEN"] : null);
        echo "
\t\t</fieldset>
\t</div>

\t</div>
</div>
</form>

";
        // line 55
        $location = "overall_footer.html";
        $namespace = false;
        if (strpos($location, '@') === 0) {
            $namespace = substr($location, 1, strpos($location, '/') - 1);
            $previous_look_up_order = $this->env->getNamespaceLookUpOrder();
            $this->env->setNamespaceLookUpOrder(array($namespace, '__main__'));
        }
        $this->env->loadTemplate("overall_footer.html")->display($context);
        if ($namespace) {
            $this->env->setNamespaceLookUpOrder($previous_look_up_order);
        }
    }

    public function getTemplateName()
    {
        return "report_body.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  193 => 55,  182 => 47,  178 => 46,  174 => 45,  161 => 34,  158 => 33,  144 => 32,  142 => 31,  137 => 29,  130 => 28,  127 => 27,  116 => 23,  108 => 22,  100 => 20,  97 => 19,  95 => 18,  75 => 16,  70 => 15,  67 => 14,  61 => 13,  51 => 10,  43 => 5,  34 => 3,  31 => 2,  19 => 1,);
    }
}
