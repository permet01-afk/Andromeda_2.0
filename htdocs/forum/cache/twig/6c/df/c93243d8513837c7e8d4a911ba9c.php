<?php

/* acp_profile.html */
class __TwigTemplate_6cdfc93243d8513837c7e8d4a911ba9c extends Twig_Template
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

";
        // line 5
        if ((isset($context["S_EDIT"]) ? $context["S_EDIT"] : null)) {
            // line 6
            echo "
\t<a href=\"";
            // line 7
            echo (isset($context["U_BACK"]) ? $context["U_BACK"] : null);
            echo "\" style=\"float: ";
            echo (isset($context["S_CONTENT_FLOW_END"]) ? $context["S_CONTENT_FLOW_END"] : null);
            echo ";\">&laquo; ";
            echo $this->env->getExtension('phpbb')->lang("BACK");
            echo "</a>

\t<h1>";
            // line 9
            echo $this->env->getExtension('phpbb')->lang("TITLE");
            echo "</h1>

\t<p>";
            // line 11
            echo $this->env->getExtension('phpbb')->lang("EXPLAIN");
            echo "</p>

\t";
            // line 13
            if ((isset($context["ERROR_MSG"]) ? $context["ERROR_MSG"] : null)) {
                // line 14
                echo "\t\t<div class=\"errorbox\">
\t\t\t<h3>";
                // line 15
                echo $this->env->getExtension('phpbb')->lang("WARNING");
                echo "</h3>
\t\t\t<p>";
                // line 16
                echo (isset($context["ERROR_MSG"]) ? $context["ERROR_MSG"] : null);
                echo "</p>
\t\t</div>
\t";
            }
            // line 19
            echo "
\t<form id=\"add_profile_field\" method=\"post\" action=\"";
            // line 20
            echo (isset($context["U_ACTION"]) ? $context["U_ACTION"] : null);
            echo "\">

\t";
            // line 22
            if ((isset($context["S_STEP_ONE"]) ? $context["S_STEP_ONE"] : null)) {
                // line 23
                echo "
\t\t<fieldset>
\t\t\t<legend>";
                // line 25
                echo $this->env->getExtension('phpbb')->lang("TITLE");
                echo "</legend>
\t\t<dl>
\t\t\t<dt><label>";
                // line 27
                echo $this->env->getExtension('phpbb')->lang("FIELD_TYPE");
                echo $this->env->getExtension('phpbb')->lang("COLON");
                echo "</label><br /><span>";
                echo $this->env->getExtension('phpbb')->lang("FIELD_TYPE_EXPLAIN");
                echo "</span></dt>
\t\t\t<dd><strong>";
                // line 28
                echo (isset($context["FIELD_TYPE"]) ? $context["FIELD_TYPE"] : null);
                echo "</strong></dd>
\t\t</dl>
\t\t";
                // line 30
                if ((isset($context["S_EDIT_MODE"]) ? $context["S_EDIT_MODE"] : null)) {
                    // line 31
                    echo "\t\t<dl>
\t\t\t<dt><label>";
                    // line 32
                    echo $this->env->getExtension('phpbb')->lang("FIELD_IDENT");
                    echo $this->env->getExtension('phpbb')->lang("COLON");
                    echo "</label><br /><span>";
                    echo $this->env->getExtension('phpbb')->lang("FIELD_IDENT_EXPLAIN");
                    echo "</span></dt>
\t\t\t<dd><input type=\"hidden\" name=\"field_ident\" value=\"";
                    // line 33
                    echo (isset($context["FIELD_IDENT"]) ? $context["FIELD_IDENT"] : null);
                    echo "\" /><strong>";
                    echo (isset($context["FIELD_IDENT"]) ? $context["FIELD_IDENT"] : null);
                    echo "</strong></dd>
\t\t</dl>
\t\t";
                } else {
                    // line 36
                    echo "\t\t<dl>
\t\t\t<dt><label for=\"field_ident\">";
                    // line 37
                    echo $this->env->getExtension('phpbb')->lang("FIELD_IDENT");
                    echo $this->env->getExtension('phpbb')->lang("COLON");
                    echo "</label><br /><span>";
                    echo $this->env->getExtension('phpbb')->lang("FIELD_IDENT_EXPLAIN");
                    echo "</span></dt>
\t\t\t<dd><input class=\"text medium\" type=\"text\" id=\"field_ident\" name=\"field_ident\" value=\"";
                    // line 38
                    echo (isset($context["FIELD_IDENT"]) ? $context["FIELD_IDENT"] : null);
                    echo "\" /></dd>
\t\t</dl>
\t\t";
                }
                // line 41
                echo "\t\t<dl>
\t\t\t<dt><label for=\"field_no_view\">";
                // line 42
                echo $this->env->getExtension('phpbb')->lang("DISPLAY_PROFILE_FIELD");
                echo $this->env->getExtension('phpbb')->lang("COLON");
                echo "</label><br /><span>";
                echo $this->env->getExtension('phpbb')->lang("DISPLAY_PROFILE_FIELD_EXPLAIN");
                echo "</span></dt>
\t\t\t<dd><label><input type=\"radio\" class=\"radio\" id=\"field_no_view\" name=\"field_no_view\" value=\"0\"";
                // line 43
                if ((!(isset($context["S_FIELD_NO_VIEW"]) ? $context["S_FIELD_NO_VIEW"] : null))) {
                    echo " checked=\"checked\"";
                }
                echo " /> ";
                echo $this->env->getExtension('phpbb')->lang("YES");
                echo "</label>
\t\t\t\t<label><input type=\"radio\" class=\"radio\" name=\"field_no_view\" value=\"1\"";
                // line 44
                if ((isset($context["S_FIELD_NO_VIEW"]) ? $context["S_FIELD_NO_VIEW"] : null)) {
                    echo " checked=\"checked\"";
                }
                echo " /> ";
                echo $this->env->getExtension('phpbb')->lang("NO");
                echo "</label></dd>
\t\t</dl>
\t\t</fieldset>

\t\t<fieldset>
\t\t\t<legend>";
                // line 49
                echo $this->env->getExtension('phpbb')->lang("VISIBILITY_OPTION");
                echo "</legend>
\t\t<dl>
\t\t\t<dt><label for=\"field_show_profile\">";
                // line 51
                echo $this->env->getExtension('phpbb')->lang("DISPLAY_AT_PROFILE");
                echo $this->env->getExtension('phpbb')->lang("COLON");
                echo "</label><br /><span>";
                echo $this->env->getExtension('phpbb')->lang("DISPLAY_AT_PROFILE_EXPLAIN");
                echo "</span></dt>
\t\t\t<dd><input type=\"checkbox\" class=\"radio\" id=\"field_show_profile\" name=\"field_show_profile\" value=\"1\"";
                // line 52
                if ((isset($context["S_SHOW_PROFILE"]) ? $context["S_SHOW_PROFILE"] : null)) {
                    echo " checked=\"checked\"";
                }
                echo " /></dd>
\t\t</dl>
\t\t<dl>
\t\t\t<dt><label for=\"field_show_on_reg\">";
                // line 55
                echo $this->env->getExtension('phpbb')->lang("DISPLAY_AT_REGISTER");
                echo $this->env->getExtension('phpbb')->lang("COLON");
                echo "</label><br /><span>";
                echo $this->env->getExtension('phpbb')->lang("DISPLAY_AT_REGISTER_EXPLAIN");
                echo "</span></dt>
\t\t\t<dd><input type=\"checkbox\" class=\"radio\" id=\"field_show_on_reg\" name=\"field_show_on_reg\" value=\"1\"";
                // line 56
                if ((isset($context["S_SHOW_ON_REG"]) ? $context["S_SHOW_ON_REG"] : null)) {
                    echo " checked=\"checked\"";
                }
                echo " /></dd>
\t\t</dl>
\t\t<dl>
\t\t\t<dt><label for=\"field_show_on_pm\">";
                // line 59
                echo $this->env->getExtension('phpbb')->lang("DISPLAY_ON_PM");
                echo $this->env->getExtension('phpbb')->lang("COLON");
                echo "</label><br /><span>";
                echo $this->env->getExtension('phpbb')->lang("DISPLAY_ON_PM_EXPLAIN");
                echo "</span></dt>
\t\t\t<dd><input type=\"checkbox\" class=\"radio\" id=\"field_show_on_pm\" name=\"field_show_on_pm\" value=\"1\"";
                // line 60
                if ((isset($context["S_SHOW_ON_PM"]) ? $context["S_SHOW_ON_PM"] : null)) {
                    echo " checked=\"checked\"";
                }
                echo " /></dd>
\t\t</dl>
\t\t<dl>
\t\t\t<dt><label for=\"field_show_on_vt\">";
                // line 63
                echo $this->env->getExtension('phpbb')->lang("DISPLAY_ON_VT");
                echo $this->env->getExtension('phpbb')->lang("COLON");
                echo "</label><br /><span>";
                echo $this->env->getExtension('phpbb')->lang("DISPLAY_ON_VT_EXPLAIN");
                echo "</span></dt>
\t\t\t<dd><input type=\"checkbox\" class=\"radio\" id=\"field_show_on_vt\" name=\"field_show_on_vt\" value=\"1\"";
                // line 64
                if ((isset($context["S_SHOW_ON_VT"]) ? $context["S_SHOW_ON_VT"] : null)) {
                    echo " checked=\"checked\"";
                }
                echo " /></dd>
\t\t</dl>
\t\t<dl>
\t\t\t<dt><label for=\"field_show_on_ml\">";
                // line 67
                echo $this->env->getExtension('phpbb')->lang("DISPLAY_ON_MEMBERLIST");
                echo $this->env->getExtension('phpbb')->lang("COLON");
                echo "</label><br /><span>";
                echo $this->env->getExtension('phpbb')->lang("DISPLAY_ON_MEMBERLIST_EXPLAIN");
                echo "</span></dt>
\t\t\t<dd><input type=\"checkbox\" class=\"radio\" id=\"field_show_on_ml\" name=\"field_show_on_ml\" value=\"1\"";
                // line 68
                if ((isset($context["S_SHOW_ON_MEMBERLIST"]) ? $context["S_SHOW_ON_MEMBERLIST"] : null)) {
                    echo " checked=\"checked\"";
                }
                echo " /></dd>
\t\t</dl>
\t\t<dl>
\t\t\t<dt><label for=\"field_required\">";
                // line 71
                echo $this->env->getExtension('phpbb')->lang("REQUIRED_FIELD");
                echo $this->env->getExtension('phpbb')->lang("COLON");
                echo "</label><br /><span>";
                echo $this->env->getExtension('phpbb')->lang("REQUIRED_FIELD_EXPLAIN");
                echo "</span></dt>
\t\t\t<dd><input type=\"checkbox\" class=\"radio\" id=\"field_required\" name=\"field_required\" value=\"1\"";
                // line 72
                if ((isset($context["S_FIELD_REQUIRED"]) ? $context["S_FIELD_REQUIRED"] : null)) {
                    echo " checked=\"checked\"";
                }
                echo " /></dd>
\t\t</dl>
\t\t<dl>
\t\t\t<dt><label for=\"field_show_novalue\">";
                // line 75
                echo $this->env->getExtension('phpbb')->lang("SHOW_NOVALUE_FIELD");
                echo $this->env->getExtension('phpbb')->lang("COLON");
                echo "</label><br /><span>";
                echo $this->env->getExtension('phpbb')->lang("SHOW_NOVALUE_FIELD_EXPLAIN");
                echo "</span></dt>
\t\t\t<dd><input type=\"checkbox\" class=\"radio\" id=\"field_show_novalue\" name=\"field_show_novalue\" value=\"1\"";
                // line 76
                if ((isset($context["S_FIELD_SHOW_NOVALUE"]) ? $context["S_FIELD_SHOW_NOVALUE"] : null)) {
                    echo " checked=\"checked\"";
                }
                echo " /></dd>
\t\t</dl>
\t\t<dl>
\t\t\t<dt><label for=\"field_hide\">";
                // line 79
                echo $this->env->getExtension('phpbb')->lang("HIDE_PROFILE_FIELD");
                echo $this->env->getExtension('phpbb')->lang("COLON");
                echo "</label><br /><span>";
                echo $this->env->getExtension('phpbb')->lang("HIDE_PROFILE_FIELD_EXPLAIN");
                echo "</span></dt>
\t\t\t<dd><input type=\"checkbox\" class=\"radio\" id=\"field_hide\" name=\"field_hide\" value=\"1\"";
                // line 80
                if ((isset($context["S_FIELD_HIDE"]) ? $context["S_FIELD_HIDE"] : null)) {
                    echo " checked=\"checked\"";
                }
                echo " /></dd>
\t\t</dl>
\t\t<dl>
\t\t\t<dt><label for=\"field_is_contact\">";
                // line 83
                echo $this->env->getExtension('phpbb')->lang("FIELD_IS_CONTACT");
                echo $this->env->getExtension('phpbb')->lang("COLON");
                echo "</label><br /><span>";
                echo $this->env->getExtension('phpbb')->lang("FIELD_IS_CONTACT_EXPLAIN");
                echo "</span></dt>
\t\t\t<dd><input type=\"checkbox\" class=\"radio\" id=\"field_is_contact\" name=\"field_is_contact\" value=\"1\"";
                // line 84
                if ((isset($context["S_FIELD_CONTACT"]) ? $context["S_FIELD_CONTACT"] : null)) {
                    echo " checked=\"checked\"";
                }
                echo " /></dd>
\t\t\t<dd><input class=\"text medium\" type=\"text\" name=\"field_contact_desc\" id=\"field_contact_desc\" value=\"";
                // line 85
                echo (isset($context["FIELD_CONTACT_DESC"]) ? $context["FIELD_CONTACT_DESC"] : null);
                echo "\" /> <label for=\"field_contact_desc\">";
                echo $this->env->getExtension('phpbb')->lang("FIELD_CONTACT_DESC");
                echo "</label></dd>
\t\t\t<dd><input class=\"text medium\" type=\"text\" name=\"field_contact_url\" id=\"field_contact_url\" value=\"";
                // line 86
                echo (isset($context["FIELD_CONTACT_URL"]) ? $context["FIELD_CONTACT_URL"] : null);
                echo "\" /> <label for=\"field_contact_url\">";
                echo $this->env->getExtension('phpbb')->lang("FIELD_CONTACT_URL");
                echo "</label></dd>
\t\t</dl>
\t\t</fieldset>

\t\t";
                // line 90
                if ((isset($context["S_EDIT_MODE"]) ? $context["S_EDIT_MODE"] : null)) {
                    // line 91
                    echo "\t\t\t<fieldset class=\"quick\">
\t\t\t\t<input class=\"button1\" type=\"submit\" name=\"save\" value=\"";
                    // line 92
                    echo $this->env->getExtension('phpbb')->lang("SAVE");
                    echo "\" />
\t\t\t</fieldset>
\t\t";
                }
                // line 95
                echo "
\t\t<fieldset>
\t\t\t<legend>";
                // line 97
                echo $this->env->getExtension('phpbb')->lang("LANG_SPECIFIC");
                echo "</legend>
\t\t<dl>
\t\t\t<dt><label for=\"lang_name\">";
                // line 99
                echo $this->env->getExtension('phpbb')->lang("USER_FIELD_NAME");
                echo $this->env->getExtension('phpbb')->lang("COLON");
                echo "</label></dt>
\t\t\t<dd><input class=\"text medium\" type=\"text\" id=\"lang_name\" name=\"lang_name\" value=\"";
                // line 100
                echo (isset($context["LANG_NAME"]) ? $context["LANG_NAME"] : null);
                echo "\" /></dd>
\t\t</dl>
\t\t<dl>
\t\t\t<dt><label for=\"lang_explain\">";
                // line 103
                echo $this->env->getExtension('phpbb')->lang("FIELD_DESCRIPTION");
                echo $this->env->getExtension('phpbb')->lang("COLON");
                echo "</label><br /><span>";
                echo $this->env->getExtension('phpbb')->lang("FIELD_DESCRIPTION_EXPLAIN");
                echo "</span></dt>
\t\t\t<dd><textarea id=\"lang_explain\" name=\"lang_explain\" rows=\"3\" cols=\"80\">";
                // line 104
                echo (isset($context["LANG_EXPLAIN"]) ? $context["LANG_EXPLAIN"] : null);
                echo "</textarea></dd>
\t\t</dl>
\t\t";
                // line 106
                if (((isset($context["S_TEXT"]) ? $context["S_TEXT"] : null) || (isset($context["S_STRING"]) ? $context["S_STRING"] : null))) {
                    // line 107
                    echo "\t\t\t<dl>
\t\t\t\t<dt><label for=\"lang_default_value\">";
                    // line 108
                    echo $this->env->getExtension('phpbb')->lang("DEFAULT_VALUE");
                    echo $this->env->getExtension('phpbb')->lang("COLON");
                    echo "</label><br /><span>";
                    echo $this->env->getExtension('phpbb')->lang("DEFAULT_VALUE_EXPLAIN");
                    echo "</span></dt>
\t\t\t\t<dd>";
                    // line 109
                    if ((isset($context["S_STRING"]) ? $context["S_STRING"] : null)) {
                        echo "<input class=\"text medium\" type=\"text\" id=\"lang_default_value\" name=\"lang_default_value\" value=\"";
                        echo (isset($context["LANG_DEFAULT_VALUE"]) ? $context["LANG_DEFAULT_VALUE"] : null);
                        echo "\" />";
                    } else {
                        echo "<textarea id=\"lang_default_value\" name=\"lang_default_value\" rows=\"5\" cols=\"80\">";
                        echo (isset($context["LANG_DEFAULT_VALUE"]) ? $context["LANG_DEFAULT_VALUE"] : null);
                        echo "</textarea>";
                    }
                    echo "</dd>
\t\t\t</dl>
\t\t";
                }
                // line 112
                echo "\t\t";
                if (((isset($context["S_BOOL"]) ? $context["S_BOOL"] : null) || (isset($context["S_DROPDOWN"]) ? $context["S_DROPDOWN"] : null))) {
                    // line 113
                    echo "\t\t\t<dl>
\t\t\t\t<dt><label for=\"lang_options\">";
                    // line 114
                    echo $this->env->getExtension('phpbb')->lang("ENTRIES");
                    echo $this->env->getExtension('phpbb')->lang("COLON");
                    echo "</label>
\t\t\t\t\t";
                    // line 115
                    if (((isset($context["S_EDIT_MODE"]) ? $context["S_EDIT_MODE"] : null) && (isset($context["S_DROPDOWN"]) ? $context["S_DROPDOWN"] : null))) {
                        // line 116
                        echo "\t\t\t\t\t\t<br /><span>";
                        echo $this->env->getExtension('phpbb')->lang("EDIT_DROPDOWN_LANG_EXPLAIN");
                        echo "</span>
\t\t\t\t\t";
                    } else {
                        // line 118
                        echo "\t\t\t\t\t\t<br /><span>";
                        echo $this->env->getExtension('phpbb')->lang("LANG_OPTIONS_EXPLAIN");
                        echo "</span>
\t\t\t\t\t";
                    }
                    // line 120
                    echo "\t\t\t\t</dt>
\t\t\t";
                    // line 121
                    if ((isset($context["S_DROPDOWN"]) ? $context["S_DROPDOWN"] : null)) {
                        // line 122
                        echo "\t\t\t\t<dd><textarea id=\"lang_options\" name=\"lang_options\" rows=\"5\" cols=\"80\">";
                        echo (isset($context["LANG_OPTIONS"]) ? $context["LANG_OPTIONS"] : null);
                        echo "</textarea></dd>
\t\t\t";
                    } else {
                        // line 124
                        echo "\t\t\t\t<dd><input class=\"medium\" id=\"lang_options\" name=\"lang_options[0]\" value=\"";
                        echo (isset($context["FIRST_LANG_OPTION"]) ? $context["FIRST_LANG_OPTION"] : null);
                        echo "\" /> ";
                        echo $this->env->getExtension('phpbb')->lang("FIRST_OPTION");
                        echo "</dd>
\t\t\t\t<dd><input class=\"medium\" name=\"lang_options[1]\" value=\"";
                        // line 125
                        echo (isset($context["SECOND_LANG_OPTION"]) ? $context["SECOND_LANG_OPTION"] : null);
                        echo "\" /> ";
                        echo $this->env->getExtension('phpbb')->lang("SECOND_OPTION");
                        echo "</dd>
\t\t\t";
                    }
                    // line 127
                    echo "\t\t\t</dl>
\t\t";
                }
                // line 129
                echo "\t\t</fieldset>

\t\t<fieldset class=\"quick\">
\t\t\t";
                // line 132
                echo (isset($context["S_HIDDEN_FIELDS"]) ? $context["S_HIDDEN_FIELDS"] : null);
                echo "
\t\t\t";
                // line 133
                echo (isset($context["S_FORM_TOKEN"]) ? $context["S_FORM_TOKEN"] : null);
                echo "
\t\t\t<input class=\"button1\" type=\"submit\" name=\"next\" value=\"";
                // line 134
                echo $this->env->getExtension('phpbb')->lang("PROFILE_TYPE_OPTIONS");
                echo "\" />
\t\t</fieldset>

\t";
            } elseif ((isset($context["S_STEP_TWO"]) ? $context["S_STEP_TWO"] : null)) {
                // line 138
                echo "
\t\t<fieldset>
\t\t\t<legend>";
                // line 140
                echo $this->env->getExtension('phpbb')->lang("TITLE");
                echo "</legend>
\t\t";
                // line 141
                $context['_parent'] = (array) $context;
                $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "option"));
                foreach ($context['_seq'] as $context["_key"] => $context["option"]) {
                    // line 142
                    echo "\t\t\t<dl>
\t\t\t\t<dt><label>";
                    // line 143
                    echo $this->getAttribute((isset($context["option"]) ? $context["option"] : null), "TITLE");
                    echo $this->env->getExtension('phpbb')->lang("COLON");
                    echo "</label>";
                    if ($this->getAttribute((isset($context["option"]) ? $context["option"] : null), "EXPLAIN")) {
                        echo "<br /><span>";
                        echo $this->getAttribute((isset($context["option"]) ? $context["option"] : null), "EXPLAIN");
                        echo "</span>";
                    }
                    echo "</dt>
\t\t\t\t<dd>";
                    // line 144
                    echo $this->getAttribute((isset($context["option"]) ? $context["option"] : null), "FIELD");
                    echo "</dd>
\t\t\t</dl>
\t\t";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['option'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 147
                echo "\t\t</fieldset>

\t\t<fieldset class=\"quick\" style=\"float: ";
                // line 149
                echo (isset($context["S_CONTENT_FLOW_BEGIN"]) ? $context["S_CONTENT_FLOW_BEGIN"] : null);
                echo ";\">
\t\t\t<input class=\"button1\" type=\"submit\" name=\"prev\" value=\"";
                // line 150
                echo $this->env->getExtension('phpbb')->lang("PROFILE_BASIC_OPTIONS");
                echo "\" />
\t\t</fieldset>

\t\t<fieldset class=\"quick\" style=\"float: ";
                // line 153
                echo (isset($context["S_CONTENT_FLOW_END"]) ? $context["S_CONTENT_FLOW_END"] : null);
                echo ";\">
\t\t\t";
                // line 154
                echo (isset($context["S_HIDDEN_FIELDS"]) ? $context["S_HIDDEN_FIELDS"] : null);
                echo "
\t\t\t";
                // line 155
                echo (isset($context["S_FORM_TOKEN"]) ? $context["S_FORM_TOKEN"] : null);
                echo "
\t\t\t<input class=\"button1\" type=\"submit\" name=\"next\" value=\"";
                // line 156
                echo $this->env->getExtension('phpbb')->lang("NEXT_STEP");
                echo "\" />
\t\t</fieldset>

\t";
            } elseif ((isset($context["S_STEP_THREE"]) ? $context["S_STEP_THREE"] : null)) {
                // line 160
                echo "
\t\t";
                // line 161
                $context['_parent'] = (array) $context;
                $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "options"));
                foreach ($context['_seq'] as $context["_key"] => $context["options"]) {
                    // line 162
                    echo "\t\t\t<fieldset>
\t\t\t\t<legend>";
                    // line 163
                    echo $this->getAttribute((isset($context["options"]) ? $context["options"] : null), "LANGUAGE");
                    echo "</legend>
\t\t\t";
                    // line 164
                    $context['_parent'] = (array) $context;
                    $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["options"]) ? $context["options"] : null), "field"));
                    foreach ($context['_seq'] as $context["_key"] => $context["field"]) {
                        // line 165
                        echo "\t\t\t\t<dl>
\t\t\t\t\t<dt><label>";
                        // line 166
                        echo $this->getAttribute((isset($context["field"]) ? $context["field"] : null), "L_TITLE");
                        echo $this->env->getExtension('phpbb')->lang("COLON");
                        echo "</label>";
                        if ($this->getAttribute((isset($context["field"]) ? $context["field"] : null), "L_EXPLAIN")) {
                            echo "<br /><span>";
                            echo $this->getAttribute((isset($context["field"]) ? $context["field"] : null), "L_EXPLAIN");
                            echo "</span>";
                        }
                        echo "</dt>
\t\t\t\t\t";
                        // line 167
                        echo $this->getAttribute((isset($context["field"]) ? $context["field"] : null), "FIELD");
                        echo "
\t\t\t\t</dl>
\t\t\t";
                    }
                    $_parent = $context['_parent'];
                    unset($context['_seq'], $context['_iterated'], $context['_key'], $context['field'], $context['_parent'], $context['loop']);
                    $context = array_intersect_key($context, $_parent) + $_parent;
                    // line 170
                    echo "\t\t\t</fieldset>
\t\t";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['options'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 172
                echo "
\t\t<fieldset class=\"quick\" style=\"float: ";
                // line 173
                echo (isset($context["S_CONTENT_FLOW_BEGIN"]) ? $context["S_CONTENT_FLOW_BEGIN"] : null);
                echo ";\">
\t\t\t<input class=\"button1\" type=\"submit\" name=\"prev\" value=\"";
                // line 174
                echo $this->env->getExtension('phpbb')->lang("PROFILE_TYPE_OPTIONS");
                echo "\" />
\t\t</fieldset>

\t\t<fieldset class=\"quick\" style=\"float: ";
                // line 177
                echo (isset($context["S_CONTENT_FLOW_END"]) ? $context["S_CONTENT_FLOW_END"] : null);
                echo ";\">
\t\t\t";
                // line 178
                echo (isset($context["S_HIDDEN_FIELDS"]) ? $context["S_HIDDEN_FIELDS"] : null);
                echo "
\t\t\t<input class=\"button1\" type=\"submit\" name=\"save\" value=\"";
                // line 179
                echo $this->env->getExtension('phpbb')->lang("SAVE");
                echo "\" />
\t\t\t";
                // line 180
                echo (isset($context["S_FORM_TOKEN"]) ? $context["S_FORM_TOKEN"] : null);
                echo "
\t\t</fieldset>

\t";
            }
            // line 184
            echo "
\t</form>

";
        } else {
            // line 188
            echo "
\t<h1>";
            // line 189
            echo $this->env->getExtension('phpbb')->lang("ACP_CUSTOM_PROFILE_FIELDS");
            echo "</h1>

\t";
            // line 191
            if ((isset($context["S_NEED_EDIT"]) ? $context["S_NEED_EDIT"] : null)) {
                // line 192
                echo "\t\t<div class=\"errorbox\">
\t\t\t<h3>";
                // line 193
                echo $this->env->getExtension('phpbb')->lang("WARNING");
                echo "</h3>
\t\t\t<p>";
                // line 194
                echo $this->env->getExtension('phpbb')->lang("CUSTOM_FIELDS_NOT_TRANSLATED");
                echo "</p>
\t\t</div>
\t";
            }
            // line 197
            echo "
\t<table class=\"table1 zebra-table\">
\t<thead>
\t<tr>
\t\t<th>";
            // line 201
            echo $this->env->getExtension('phpbb')->lang("FIELD_IDENT");
            echo "</th>
\t\t<th>";
            // line 202
            echo $this->env->getExtension('phpbb')->lang("FIELD_TYPE");
            echo "</th>
\t\t<th colspan=\"2\">";
            // line 203
            echo $this->env->getExtension('phpbb')->lang("OPTIONS");
            echo "</th>
\t</tr>
\t</thead>
\t<tbody>
\t";
            // line 207
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "fields"));
            $context['_iterated'] = false;
            foreach ($context['_seq'] as $context["_key"] => $context["fields"]) {
                // line 208
                echo "\t<tr>
\t\t<td>";
                // line 209
                echo $this->getAttribute((isset($context["fields"]) ? $context["fields"] : null), "FIELD_IDENT");
                echo "</td>
\t\t<td>";
                // line 210
                echo $this->getAttribute((isset($context["fields"]) ? $context["fields"] : null), "FIELD_TYPE");
                echo "</td>
\t\t<td style=\"text-align: center;\"><a href=\"";
                // line 211
                echo $this->getAttribute((isset($context["fields"]) ? $context["fields"] : null), "U_ACTIVATE_DEACTIVATE");
                echo "\" data-ajax=\"activate_deactivate\">";
                echo $this->getAttribute((isset($context["fields"]) ? $context["fields"] : null), "L_ACTIVATE_DEACTIVATE");
                echo "</a>";
                if ($this->getAttribute((isset($context["fields"]) ? $context["fields"] : null), "S_NEED_EDIT")) {
                    echo " | <a href=\"";
                    echo $this->getAttribute((isset($context["fields"]) ? $context["fields"] : null), "U_TRANSLATE");
                    echo "\" style=\"color: red;\">";
                    echo $this->env->getExtension('phpbb')->lang("TRANSLATE");
                    echo "</a>";
                }
                echo "</td>

\t\t<td class=\"actions\" style=\"width: 80px;\">
\t\t\t<span class=\"up-disabled\" style=\"display:none;\">";
                // line 214
                echo (isset($context["ICON_MOVE_UP_DISABLED"]) ? $context["ICON_MOVE_UP_DISABLED"] : null);
                echo "</span>
\t\t\t<span class=\"up\"><a href=\"";
                // line 215
                echo $this->getAttribute((isset($context["fields"]) ? $context["fields"] : null), "U_MOVE_UP");
                echo "\" data-ajax=\"row_up\">";
                echo (isset($context["ICON_MOVE_UP"]) ? $context["ICON_MOVE_UP"] : null);
                echo "</a></span>
\t\t\t<span class=\"down-disabled\" style=\"display:none;\">";
                // line 216
                echo (isset($context["ICON_MOVE_DOWN_DISABLED"]) ? $context["ICON_MOVE_DOWN_DISABLED"] : null);
                echo "</span>
\t\t\t<span class=\"down\"><a href=\"";
                // line 217
                echo $this->getAttribute((isset($context["fields"]) ? $context["fields"] : null), "U_MOVE_DOWN");
                echo "\" data-ajax=\"row_down\">";
                echo (isset($context["ICON_MOVE_DOWN"]) ? $context["ICON_MOVE_DOWN"] : null);
                echo "</a></span>
\t\t\t";
                // line 218
                if ((!$this->getAttribute((isset($context["fields"]) ? $context["fields"] : null), "S_NEED_EDIT"))) {
                    // line 219
                    echo "\t\t\t\t<a href=\"";
                    echo $this->getAttribute((isset($context["fields"]) ? $context["fields"] : null), "U_EDIT");
                    echo "\">";
                    echo (isset($context["ICON_EDIT"]) ? $context["ICON_EDIT"] : null);
                    echo "</a>
\t\t\t";
                } else {
                    // line 221
                    echo "\t\t\t\t";
                    echo (isset($context["ICON_EDIT_DISABLED"]) ? $context["ICON_EDIT_DISABLED"] : null);
                    echo "
\t\t\t";
                }
                // line 223
                echo "\t\t\t<a href=\"";
                echo $this->getAttribute((isset($context["fields"]) ? $context["fields"] : null), "U_DELETE");
                echo "\" data-ajax=\"row_delete\">";
                echo (isset($context["ICON_DELETE"]) ? $context["ICON_DELETE"] : null);
                echo "</a>
\t\t</td>

\t</tr>
\t";
                $context['_iterated'] = true;
            }
            if (!$context['_iterated']) {
                // line 228
                echo "\t<tr class=\"row3\">
\t\t<td colspan=\"4\">";
                // line 229
                echo $this->env->getExtension('phpbb')->lang("ACP_NO_ITEMS");
                echo "</td>
\t</tr>
\t";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['fields'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 232
            echo "\t</tbody>
\t</table>

\t<form id=\"profile_fields\" method=\"post\" action=\"";
            // line 235
            echo (isset($context["U_ACTION"]) ? $context["U_ACTION"] : null);
            echo "\">

\t<fieldset class=\"quick\">
\t\t<input class=\"text small\" type=\"text\" name=\"field_ident\" /> <select name=\"field_type\">";
            // line 238
            echo (isset($context["S_TYPE_OPTIONS"]) ? $context["S_TYPE_OPTIONS"] : null);
            echo "</select>
\t\t<input class=\"button1\" type=\"submit\" name=\"submit\" value=\"";
            // line 239
            echo $this->env->getExtension('phpbb')->lang("CREATE_NEW_FIELD");
            echo "\" />
\t\t<input type=\"hidden\" name=\"create\" value=\"1\" />
\t\t";
            // line 241
            echo (isset($context["S_FORM_TOKEN"]) ? $context["S_FORM_TOKEN"] : null);
            echo "
\t</fieldset>
\t</form>

";
        }
        // line 246
        echo "
";
        // line 247
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
        return "acp_profile.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  790 => 247,  787 => 246,  779 => 241,  774 => 239,  770 => 238,  764 => 235,  759 => 232,  750 => 229,  747 => 228,  734 => 223,  728 => 221,  720 => 219,  718 => 218,  712 => 217,  708 => 216,  702 => 215,  698 => 214,  682 => 211,  678 => 210,  674 => 209,  671 => 208,  666 => 207,  659 => 203,  655 => 202,  651 => 201,  645 => 197,  639 => 194,  635 => 193,  632 => 192,  630 => 191,  625 => 189,  622 => 188,  616 => 184,  609 => 180,  605 => 179,  601 => 178,  597 => 177,  591 => 174,  587 => 173,  584 => 172,  577 => 170,  568 => 167,  557 => 166,  554 => 165,  550 => 164,  546 => 163,  543 => 162,  539 => 161,  536 => 160,  529 => 156,  525 => 155,  521 => 154,  517 => 153,  511 => 150,  507 => 149,  503 => 147,  494 => 144,  483 => 143,  480 => 142,  476 => 141,  472 => 140,  468 => 138,  461 => 134,  457 => 133,  453 => 132,  448 => 129,  444 => 127,  437 => 125,  430 => 124,  424 => 122,  422 => 121,  419 => 120,  413 => 118,  407 => 116,  405 => 115,  400 => 114,  397 => 113,  394 => 112,  380 => 109,  373 => 108,  370 => 107,  368 => 106,  363 => 104,  356 => 103,  350 => 100,  345 => 99,  340 => 97,  336 => 95,  330 => 92,  327 => 91,  325 => 90,  316 => 86,  310 => 85,  304 => 84,  297 => 83,  289 => 80,  282 => 79,  274 => 76,  267 => 75,  259 => 72,  252 => 71,  244 => 68,  237 => 67,  229 => 64,  222 => 63,  214 => 60,  207 => 59,  199 => 56,  192 => 55,  184 => 52,  177 => 51,  172 => 49,  160 => 44,  152 => 43,  145 => 42,  142 => 41,  136 => 38,  129 => 37,  126 => 36,  118 => 33,  111 => 32,  108 => 31,  106 => 30,  101 => 28,  94 => 27,  89 => 25,  85 => 23,  83 => 22,  78 => 20,  75 => 19,  69 => 16,  65 => 15,  62 => 14,  60 => 13,  55 => 11,  50 => 9,  41 => 7,  38 => 6,  36 => 5,  31 => 2,  19 => 1,);
    }
}
