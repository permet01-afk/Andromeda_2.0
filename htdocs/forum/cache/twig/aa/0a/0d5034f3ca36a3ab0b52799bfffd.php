<?php

/* acp_ban.html */
class __TwigTemplate_aa0a0d5034f3ca36a3ab0b52799bfffd extends Twig_Template
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
<a id=\"maincontent\"></a>

<p>";
        // line 5
        echo $this->env->getExtension('phpbb')->lang("ACP_BAN_EXPLAIN");
        echo "</p>

<h1>";
        // line 7
        echo $this->env->getExtension('phpbb')->lang("TITLE");
        echo "</h1>

<p>";
        // line 9
        echo $this->env->getExtension('phpbb')->lang("EXPLAIN");
        echo "</p>

<script type=\"text/javascript\">
// <![CDATA[

\tvar ban_length = new Array();
\t\tban_length[-1] = '';
\tvar ban_reason = new Array();
\t\tban_reason[-1] = '';
\tvar ban_give_reason = new Array();
\t\tban_give_reason[-1] = '';

\t";
        // line 21
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "bans"));
        foreach ($context['_seq'] as $context["_key"] => $context["bans"]) {
            // line 22
            echo "\t\tban_length['";
            echo $this->getAttribute((isset($context["bans"]) ? $context["bans"] : null), "BAN_ID");
            echo "'] = '";
            echo $this->getAttribute((isset($context["bans"]) ? $context["bans"] : null), "A_LENGTH");
            echo "';
\t\t";
            // line 23
            if ($this->getAttribute((isset($context["bans"]) ? $context["bans"] : null), "A_REASON")) {
                // line 24
                echo "\t\t\tban_reason['";
                echo $this->getAttribute((isset($context["bans"]) ? $context["bans"] : null), "BAN_ID");
                echo "'] = '";
                echo $this->getAttribute((isset($context["bans"]) ? $context["bans"] : null), "A_REASON");
                echo "';
\t\t";
            }
            // line 26
            echo "\t\t";
            if ($this->getAttribute((isset($context["bans"]) ? $context["bans"] : null), "A_GIVE_REASON")) {
                // line 27
                echo "\t\t\tban_give_reason['";
                echo $this->getAttribute((isset($context["bans"]) ? $context["bans"] : null), "BAN_ID");
                echo "'] = '";
                echo $this->getAttribute((isset($context["bans"]) ? $context["bans"] : null), "A_GIVE_REASON");
                echo "';
\t\t";
            }
            // line 29
            echo "\t";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['bans'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 30
        echo "
\tfunction display_details(option)
\t{
\t\tdocument.getElementById('unbanlength').value = ban_length[option];
\t\tif (option in ban_reason) {
\t\t\tdocument.getElementById('unbanreason').innerHTML = ban_reason[option];
\t\t} else {
\t\t\tdocument.getElementById('unbanreason').innerHTML = '';
\t\t}
\t\tif (option in ban_give_reason) {
\t\t\tdocument.getElementById('unbangivereason').innerHTML = ban_give_reason[option];
\t\t} else {
\t\t\tdocument.getElementById('unbangivereason').innerHTML = '';
\t\t}
\t}

// ]]>
</script>

<form id=\"acp_ban\" method=\"post\" action=\"";
        // line 49
        echo (isset($context["U_ACTION"]) ? $context["U_ACTION"] : null);
        echo "\">

<fieldset>
\t<legend>";
        // line 52
        echo $this->env->getExtension('phpbb')->lang("TITLE");
        echo "</legend>
<dl>
\t<dt><label for=\"ban\">";
        // line 54
        echo $this->env->getExtension('phpbb')->lang("BAN_CELL");
        echo $this->env->getExtension('phpbb')->lang("COLON");
        echo "</label></dt>
\t<dd><textarea name=\"ban\" cols=\"40\" rows=\"3\" id=\"ban\"></textarea></dd>
\t";
        // line 56
        if ((isset($context["S_USERNAME_BAN"]) ? $context["S_USERNAME_BAN"] : null)) {
            echo "<dd>[ <a href=\"";
            echo (isset($context["U_FIND_USERNAME"]) ? $context["U_FIND_USERNAME"] : null);
            echo "\" onclick=\"find_username(this.href); return false;\">";
            echo $this->env->getExtension('phpbb')->lang("FIND_USERNAME");
            echo "</a> ]</dd>";
        }
        // line 57
        echo "</dl>
<dl>
\t<dt><label for=\"banlength\">";
        // line 59
        echo $this->env->getExtension('phpbb')->lang("BAN_LENGTH");
        echo $this->env->getExtension('phpbb')->lang("COLON");
        echo "</label></dt>
\t<dd><label for=\"banlength\"><select name=\"banlength\" id=\"banlength\" onchange=\"if(this.value==-1){document.getElementById('banlengthother').style.display = 'block';}else{document.getElementById('banlengthother').style.display='none';}\">";
        // line 60
        echo (isset($context["S_BAN_END_OPTIONS"]) ? $context["S_BAN_END_OPTIONS"] : null);
        echo "</select></label></dd>
\t<dd id=\"banlengthother\" style=\"display: none;\"><label><input type=\"text\" name=\"banlengthother\" class=\"inputbox\" /><br /><span>";
        // line 61
        echo $this->env->getExtension('phpbb')->lang("YEAR_MONTH_DAY");
        echo "</span></label></dd>
</dl>
<dl>
\t<dt><label for=\"banexclude\">";
        // line 64
        echo $this->env->getExtension('phpbb')->lang("BAN_EXCLUDE");
        echo $this->env->getExtension('phpbb')->lang("COLON");
        echo "</label><br /><span>";
        echo $this->env->getExtension('phpbb')->lang("BAN_EXCLUDE_EXPLAIN");
        echo "</span></dt>
\t<dd><label><input type=\"radio\" name=\"banexclude\" value=\"1\" class=\"radio\" /> ";
        // line 65
        echo $this->env->getExtension('phpbb')->lang("YES");
        echo "</label>
\t\t<label><input type=\"radio\" name=\"banexclude\" id=\"banexclude\" value=\"0\" checked=\"checked\" class=\"radio\" /> ";
        // line 66
        echo $this->env->getExtension('phpbb')->lang("NO");
        echo "</label></dd>
</dl>
<dl>
\t<dt><label for=\"banreason\">";
        // line 69
        echo $this->env->getExtension('phpbb')->lang("BAN_REASON");
        echo $this->env->getExtension('phpbb')->lang("COLON");
        echo "</label></dt>
\t<dd><input name=\"banreason\" type=\"text\" class=\"text medium\" maxlength=\"255\" id=\"banreason\" /></dd>
</dl>
<dl>
\t<dt><label for=\"bangivereason\">";
        // line 73
        echo $this->env->getExtension('phpbb')->lang("BAN_GIVE_REASON");
        echo $this->env->getExtension('phpbb')->lang("COLON");
        echo "</label></dt>
\t<dd><input name=\"bangivereason\" type=\"text\" class=\"text medium\" maxlength=\"255\" id=\"bangivereason\" /></dd>
</dl>

<p class=\"submit-buttons\">
\t<input class=\"button1\" type=\"submit\" id=\"bansubmit\" name=\"bansubmit\" value=\"";
        // line 78
        echo $this->env->getExtension('phpbb')->lang("SUBMIT");
        echo "\" />&nbsp;
\t<input class=\"button2\" type=\"reset\" id=\"banreset\" name=\"banreset\" value=\"";
        // line 79
        echo $this->env->getExtension('phpbb')->lang("RESET");
        echo "\" />
</p>
";
        // line 81
        echo (isset($context["S_FORM_TOKEN"]) ? $context["S_FORM_TOKEN"] : null);
        echo "
</fieldset>
</form>

<br /><br />

<h1>";
        // line 87
        echo $this->env->getExtension('phpbb')->lang("UNBAN_TITLE");
        echo "</h1>

<p>";
        // line 89
        echo $this->env->getExtension('phpbb')->lang("UNBAN_EXPLAIN");
        echo "</p>

<form id=\"acp_unban\" method=\"post\" action=\"";
        // line 91
        echo (isset($context["U_ACTION"]) ? $context["U_ACTION"] : null);
        echo "\">

<fieldset>
\t<legend>";
        // line 94
        echo $this->env->getExtension('phpbb')->lang("UNBAN_TITLE");
        echo "</legend>

";
        // line 96
        if ((isset($context["S_BANNED_OPTIONS"]) ? $context["S_BANNED_OPTIONS"] : null)) {
            // line 97
            echo "\t<dl>
\t\t<dt><label for=\"unban\">";
            // line 98
            echo $this->env->getExtension('phpbb')->lang("BAN_CELL");
            echo $this->env->getExtension('phpbb')->lang("COLON");
            echo "</label></dt>
\t\t<dd><select id=\"unban\" name=\"unban[]\" multiple=\"multiple\" size=\"10\" style=\"width: 50%\" onchange=\"if (this.selectedIndex > -1) display_details(this.options[this.selectedIndex].value); else display_details(-1);\">";
            // line 99
            echo (isset($context["BANNED_OPTIONS"]) ? $context["BANNED_OPTIONS"] : null);
            echo "</select></dd>
\t</dl>
\t<dl>
\t\t<dt><label for=\"unbanlength\">";
            // line 102
            echo $this->env->getExtension('phpbb')->lang("BAN_LENGTH");
            echo $this->env->getExtension('phpbb')->lang("COLON");
            echo "</label></dt>
\t\t<dd><input style=\"border: 0;\" type=\"text\" class=\"text full\" readonly=\"readonly\" name=\"unbanlength\" id=\"unbanlength\" /></dd>
\t</dl>
\t<dl>
\t\t<dt><label for=\"unbanreason\">";
            // line 106
            echo $this->env->getExtension('phpbb')->lang("BAN_REASON");
            echo $this->env->getExtension('phpbb')->lang("COLON");
            echo "</label></dt>
\t\t<dd><textarea style=\"border: 0;\" class=\"text full\" readonly=\"readonly\" name=\"unbanreason\" id=\"unbanreason\" rows=\"5\" cols=\"80\">&nbsp;</textarea></dd>
\t</dl>
\t<dl>
\t\t<dt><label for=\"unbangivereason\">";
            // line 110
            echo $this->env->getExtension('phpbb')->lang("BAN_GIVE_REASON");
            echo $this->env->getExtension('phpbb')->lang("COLON");
            echo "</label></dt>
\t\t<dd><textarea style=\"border: 0;\" class=\"text full\" readonly=\"readonly\" name=\"unbangivereason\" id=\"unbangivereason\" rows=\"5\" cols=\"80\">&nbsp;</textarea></dd>
\t</dl>

\t<p class=\"submit-buttons\">
\t\t<input class=\"button1\" type=\"submit\" id=\"unbansubmit\" name=\"unbansubmit\" value=\"";
            // line 115
            echo $this->env->getExtension('phpbb')->lang("SUBMIT");
            echo "\" />&nbsp;
\t\t<input class=\"button2\" type=\"reset\" id=\"unbanreset\" name=\"unbanreset\" value=\"";
            // line 116
            echo $this->env->getExtension('phpbb')->lang("RESET");
            echo "\" />
\t</p>
\t";
            // line 118
            echo (isset($context["S_FORM_TOKEN"]) ? $context["S_FORM_TOKEN"] : null);
            echo "
\t</fieldset>

";
        } else {
            // line 122
            echo "
\t<p>";
            // line 123
            echo $this->env->getExtension('phpbb')->lang("NO_BAN_CELL");
            echo "</p>
\t";
            // line 124
            echo (isset($context["S_FORM_TOKEN"]) ? $context["S_FORM_TOKEN"] : null);
            echo "
</fieldset>

";
        }
        // line 128
        echo "
</form>

";
        // line 131
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
        return "acp_ban.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  313 => 131,  308 => 128,  301 => 124,  297 => 123,  294 => 122,  287 => 118,  282 => 116,  278 => 115,  269 => 110,  261 => 106,  253 => 102,  247 => 99,  242 => 98,  239 => 97,  237 => 96,  232 => 94,  226 => 91,  221 => 89,  216 => 87,  207 => 81,  202 => 79,  198 => 78,  189 => 73,  181 => 69,  175 => 66,  171 => 65,  164 => 64,  158 => 61,  154 => 60,  149 => 59,  145 => 57,  137 => 56,  131 => 54,  126 => 52,  120 => 49,  99 => 30,  93 => 29,  85 => 27,  82 => 26,  74 => 24,  72 => 23,  65 => 22,  61 => 21,  46 => 9,  41 => 7,  36 => 5,  31 => 2,  19 => 1,);
    }
}
