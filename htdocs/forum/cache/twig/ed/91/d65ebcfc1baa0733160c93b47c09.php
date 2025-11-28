<?php

/* viewonline_body.html */
class __TwigTemplate_ed91d65ebcfc1baa0733160c93b47c09 extends Twig_Template
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
<h2 class=\"viewonline-title\">";
        // line 3
        echo (isset($context["TOTAL_REGISTERED_USERS_ONLINE"]) ? $context["TOTAL_REGISTERED_USERS_ONLINE"] : null);
        echo "</h2>
<p>";
        // line 4
        echo (isset($context["TOTAL_GUEST_USERS_ONLINE"]) ? $context["TOTAL_GUEST_USERS_ONLINE"] : null);
        if ((isset($context["S_SWITCH_GUEST_DISPLAY"]) ? $context["S_SWITCH_GUEST_DISPLAY"] : null)) {
            echo " &bull; <a href=\"";
            echo (isset($context["U_SWITCH_GUEST_DISPLAY"]) ? $context["U_SWITCH_GUEST_DISPLAY"] : null);
            echo "\">";
            echo $this->env->getExtension('phpbb')->lang("SWITCH_GUEST_DISPLAY");
            echo "</a>";
        }
        echo "</p>

<div class=\"action-bar top\">
\t<div class=\"pagination\">
\t\t";
        // line 8
        if (twig_length_filter($this->env, $this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "pagination"))) {
            echo " 
\t\t\t";
            // line 9
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
            // line 10
            echo "\t\t";
        } else {
            echo " 
\t\t\t";
            // line 11
            echo (isset($context["PAGE_NUMBER"]) ? $context["PAGE_NUMBER"] : null);
            echo "
\t\t";
        }
        // line 13
        echo "\t</div>
</div>

<div class=\"forumbg forumbg-table\">
\t<div class=\"inner\">
\t
\t<table class=\"table1\">

\t";
        // line 21
        if (twig_length_filter($this->env, $this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "user_row"))) {
            // line 22
            echo "\t\t<thead>
\t\t<tr>
\t\t\t<th class=\"name\"><a href=\"";
            // line 24
            echo (isset($context["U_SORT_USERNAME"]) ? $context["U_SORT_USERNAME"] : null);
            echo "\">";
            echo $this->env->getExtension('phpbb')->lang("USERNAME");
            echo "</a></th>
\t\t\t<th class=\"info\"><a href=\"";
            // line 25
            echo (isset($context["U_SORT_LOCATION"]) ? $context["U_SORT_LOCATION"] : null);
            echo "\">";
            echo $this->env->getExtension('phpbb')->lang("FORUM_LOCATION");
            echo "</a></th>
\t\t\t<th class=\"active\"><a href=\"";
            // line 26
            echo (isset($context["U_SORT_UPDATED"]) ? $context["U_SORT_UPDATED"] : null);
            echo "\">";
            echo $this->env->getExtension('phpbb')->lang("LAST_UPDATED");
            echo "</a></th>
\t\t</tr>
\t\t</thead>
\t\t<tbody>
\t\t";
            // line 30
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "user_row"));
            foreach ($context['_seq'] as $context["_key"] => $context["user_row"]) {
                // line 31
                echo "\t\t<tr class=\"";
                if (($this->getAttribute((isset($context["user_row"]) ? $context["user_row"] : null), "S_ROW_COUNT") % 2 == 1)) {
                    echo "bg1";
                } else {
                    echo "bg2";
                }
                echo "\">
\t\t\t<td>";
                // line 32
                echo $this->getAttribute((isset($context["user_row"]) ? $context["user_row"] : null), "USERNAME_FULL");
                if ($this->getAttribute((isset($context["user_row"]) ? $context["user_row"] : null), "USER_IP")) {
                    echo " <span style=\"float: ";
                    echo (isset($context["S_CONTENT_FLOW_END"]) ? $context["S_CONTENT_FLOW_END"] : null);
                    echo ";\">";
                    echo $this->env->getExtension('phpbb')->lang("IP");
                    echo $this->env->getExtension('phpbb')->lang("COLON");
                    echo " <a href=\"";
                    echo $this->getAttribute((isset($context["user_row"]) ? $context["user_row"] : null), "U_USER_IP");
                    echo "\">";
                    echo $this->getAttribute((isset($context["user_row"]) ? $context["user_row"] : null), "USER_IP");
                    echo "</a> &#187; <a href=\"";
                    echo $this->getAttribute((isset($context["user_row"]) ? $context["user_row"] : null), "U_WHOIS");
                    echo "\" onclick=\"popup(this.href, 750, 500); return false;\">";
                    echo $this->env->getExtension('phpbb')->lang("WHOIS");
                    echo "</a></span>";
                }
                // line 33
                echo "\t\t\t\t";
                if ($this->getAttribute((isset($context["user_row"]) ? $context["user_row"] : null), "USER_BROWSER")) {
                    echo "<br />";
                    echo $this->getAttribute((isset($context["user_row"]) ? $context["user_row"] : null), "USER_BROWSER");
                }
                echo "</td>
\t\t\t<td class=\"info\"><a href=\"";
                // line 34
                echo $this->getAttribute((isset($context["user_row"]) ? $context["user_row"] : null), "U_FORUM_LOCATION");
                echo "\">";
                echo $this->getAttribute((isset($context["user_row"]) ? $context["user_row"] : null), "FORUM_LOCATION");
                echo "</a></td>
\t\t\t<td class=\"active\">";
                // line 35
                echo $this->getAttribute((isset($context["user_row"]) ? $context["user_row"] : null), "LASTUPDATE");
                echo "</td>
\t\t</tr>
\t\t";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['user_row'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 38
            echo "\t";
        } else {
            // line 39
            echo "\t\t<tbody>
\t\t<tr class=\"bg1\">
\t\t\t<td colspan=\"3\">";
            // line 41
            echo $this->env->getExtension('phpbb')->lang("NO_ONLINE_USERS");
            if ((isset($context["S_SWITCH_GUEST_DISPLAY"]) ? $context["S_SWITCH_GUEST_DISPLAY"] : null)) {
                echo " &bull; <a href=\"";
                echo (isset($context["U_SWITCH_GUEST_DISPLAY"]) ? $context["U_SWITCH_GUEST_DISPLAY"] : null);
                echo "\">";
                echo $this->env->getExtension('phpbb')->lang("SWITCH_GUEST_DISPLAY");
                echo "</a>";
            }
            echo "</td>
\t\t</tr>
\t";
        }
        // line 44
        echo "\t</tbody>
\t</table>
\t
\t</div>
</div>

";
        // line 50
        if ((isset($context["LEGEND"]) ? $context["LEGEND"] : null)) {
            echo "<p><em>";
            echo $this->env->getExtension('phpbb')->lang("LEGEND");
            echo $this->env->getExtension('phpbb')->lang("COLON");
            echo " ";
            echo (isset($context["LEGEND"]) ? $context["LEGEND"] : null);
            echo "</em></p>";
        }
        // line 51
        echo "
<div class=\"action-bar bottom\">
\t<div class=\"pagination\">
\t\t";
        // line 54
        if (twig_length_filter($this->env, $this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "pagination"))) {
            echo " 
\t\t\t";
            // line 55
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
            // line 56
            echo "\t\t";
        } else {
            echo " 
\t\t\t";
            // line 57
            echo (isset($context["PAGE_NUMBER"]) ? $context["PAGE_NUMBER"] : null);
            echo "
\t\t";
        }
        // line 59
        echo "\t</div>
</div>

";
        // line 62
        $location = "jumpbox.html";
        $namespace = false;
        if (strpos($location, '@') === 0) {
            $namespace = substr($location, 1, strpos($location, '/') - 1);
            $previous_look_up_order = $this->env->getNamespaceLookUpOrder();
            $this->env->setNamespaceLookUpOrder(array($namespace, '__main__'));
        }
        $this->env->loadTemplate("jumpbox.html")->display($context);
        if ($namespace) {
            $this->env->setNamespaceLookUpOrder($previous_look_up_order);
        }
        // line 63
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
        return "viewonline_body.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  254 => 63,  242 => 62,  237 => 59,  232 => 57,  227 => 56,  215 => 55,  211 => 54,  206 => 51,  197 => 50,  189 => 44,  176 => 41,  172 => 39,  169 => 38,  160 => 35,  154 => 34,  146 => 33,  128 => 32,  119 => 31,  115 => 30,  106 => 26,  100 => 25,  94 => 24,  90 => 22,  88 => 21,  78 => 13,  73 => 11,  68 => 10,  56 => 9,  52 => 8,  38 => 4,  34 => 3,  31 => 2,  19 => 1,);
    }
}
