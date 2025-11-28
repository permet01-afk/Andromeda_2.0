package net.bigpoint.as3chat 
{
    import flash.display.*;
    import net.bigpoint.flashcorelib.resources.*;
    
    public class MyTabBar extends flash.display.MovieClip
    {
        public function MyTabBar(arg1:net.bigpoint.as3chat.Chat, arg2:flash.display.Bitmap, arg3:int)
        {
            this.tabs = new Array();
            super();
            this.chat = arg1;
            this.tabImage = arg2;
            this.tabFontFace = String(net.bigpoint.flashcorelib.resources.SettingsHandler.getInstance().getVariable("tabFontFace"));
            this.tabFontSize = int(net.bigpoint.flashcorelib.resources.SettingsHandler.getInstance().getVariable("tabFontSize"));
            this.tabFontNormalColor = String(net.bigpoint.flashcorelib.resources.SettingsHandler.getInstance().getVariable("tabFontNormalColor"));
            this.tabFontSelectedColor = String(net.bigpoint.flashcorelib.resources.SettingsHandler.getInstance().getVariable("tabFontSelectedColor"));
            this.tabFontOverColor = String(net.bigpoint.flashcorelib.resources.SettingsHandler.getInstance().getVariable("tabFontOverColor"));
            this.tabFontBold = this.parseBoolean(String(net.bigpoint.flashcorelib.resources.SettingsHandler.getInstance().getVariable("tabFontBold")));
            this.tabFontItalic = this.parseBoolean(String(net.bigpoint.flashcorelib.resources.SettingsHandler.getInstance().getVariable("tabFontItalic")));
            this.gap = parseInt(String(net.bigpoint.flashcorelib.resources.SettingsHandler.getInstance().getVariable("tabGap")));
            if (net.bigpoint.flashcorelib.resources.SettingsHandler.getInstance().getVariable("tabAlphaUnselected") != null) 
            {
                tabAlphaUnselected = parseInt(String(net.bigpoint.flashcorelib.resources.SettingsHandler.getInstance().getVariable("tabAlphaUnselected")));
            }
            this.maskLength = arg3;
            if (!this.maskSet) 
            {
                this.setMask();
            }
            return;
        }

        public function getTabs():Array
        {
            return this.tabs;
        }

        public function updateTab(arg1:Number=0):void
        {
            var loc2:*=null;
            this.count = arg1 * (this.tabImage.width + this.gap) - this.gap;
            var loc1:*=0;
            while (loc1 < this.tabs.length) 
            {
                loc2 = this.tabs[loc1] as net.bigpoint.as3chat.TabElement;
                loc2.getTextField().width = loc2.getBgImage().width;
                loc2.x = this.count;
                addChild(loc2);
                this.count = this.count + loc2.getTabWidth() + this.gap;
                ++loc1;
            }
            return;
        }

        internal function setMask():void
        {
            this.tabMask = new flash.display.Sprite();
            this.tabMask.x = this.gap * -1;
            this.tabMask.graphics.beginFill(16763904);
            this.tabMask.graphics.drawRect(0, 0, this.maskLength, 50);
            this.addChild(this.tabMask);
            this.mask = this.tabMask;
            this.maskSet = true;
            return;
        }

        public function setMaskWidth(arg1:int):void
        {
            if (this.tabMask != null) 
            {
                this.tabMask.width = arg1;
            }
            return;
        }

        public function clearAll():void
        {
            var loc2:*=null;
            var loc1:*=0;
            while (loc1 < this.tabs.length) 
            {
                loc2 = this.tabs[loc1] as net.bigpoint.as3chat.TabElement;
                removeChild(loc2);
                loc2.cleanup();
                ++loc1;
            }
            this.tabs = new Array();
            return;
        }

        public function tabClick(arg1:int):void
        {
            this.chat.onTabChange(arg1);
            return;
        }

        public function selectTab(arg1:int):void
        {
            var loc2:*=null;
            var loc1:*=0;
            while (loc1 < this.tabs.length) 
            {
                loc2 = this.tabs[loc1] as net.bigpoint.as3chat.TabElement;
                if (loc2.getRoomId() != arg1) 
                {
                    loc2.setSelected(false);
                }
                else 
                {
                    loc2.setSelected(true);
                }
                ++loc1;
            }
            return;
        }

        public function selectTabByIndex(arg1:int):void
        {
            var loc1:*=this.tabs[arg1] as net.bigpoint.as3chat.TabElement;
            if (loc1 != null) 
            {
                loc1.setSelected(true);
            }
            return;
        }

        public function getSelectedIndex():int
        {
            var loc2:*=null;
            var loc1:*=0;
            while (loc1 < this.tabs.length) 
            {
                loc2 = this.tabs[loc1] as net.bigpoint.as3chat.TabElement;
                if (loc2.isSelected()) 
                {
                    return loc1;
                }
                ++loc1;
            }
            return -1;
        }

        public function getSelectedRoomId():int
        {
            var loc2:*=null;
            var loc1:*=0;
            while (loc1 < this.tabs.length) 
            {
                loc2 = this.tabs[loc1] as net.bigpoint.as3chat.TabElement;
                if (loc2.isSelected()) 
                {
                    return loc2.getRoomId();
                }
                ++loc1;
            }
            return -1;
        }

        public function getTabMask():flash.display.Sprite
        {
            return this.tabMask;
        }

        public function scrollToRight():void
        {
            var loc2:*=null;
            var loc1:*=0;
            while (loc1 < this.tabs.length) 
            {
                loc2 = this.tabs[loc1] as net.bigpoint.as3chat.TabElement;
                removeChild(loc2);
                ++loc1;
            }
            var loc3:*;
            var loc4:*;
            this.updateTab(loc3.pos = loc4 = ((loc3 = this).pos - 1));
            return;
        }

        public function scrollToLeft():void
        {
            var loc2:*=null;
            var loc1:*=0;
            while (loc1 < this.tabs.length) 
            {
                loc2 = this.tabs[loc1] as net.bigpoint.as3chat.TabElement;
                removeChild(loc2);
                ++loc1;
            }
            var loc3:*;
            var loc4:*;
            this.updateTab(loc3.pos = loc4 = ((loc3 = this).pos + 1));
            return;
        }

        public function showRightButton():Boolean
        {
            var loc1:*=(this.tabs.length + this.pos) * (this.tabImage.width + this.gap) - this.gap;
            if (loc1 < this.tabMask.width) 
            {
                return false;
            }
            return true;
        }

        public function showLeftButton():Boolean
        {
            if (this.pos < 0) 
            {
                return true;
            }
            return false;
        }

        
        {
            tabAlphaUnselected = 100;
        }

        public function addItem(arg1:int, arg2:String, arg3:String, arg4:int):void
        {
            var loc1:*=new net.bigpoint.as3chat.TabElement(this, this.tabImage.bitmapData, arg1, arg2, arg3, arg4);
            this.tabs.push(loc1);
            return;
        }

        public function parseBoolean(arg1:String):Boolean
        {
            arg1 = arg1.toLowerCase();
            if (arg1 == "true") 
            {
                return true;
            }
            return false;
        }

        internal var tabImage:flash.display.Bitmap;

        internal var tabs:Array;

        public var tabFontFace:String;

        public var tabFontSize:int;

        public var tabFontNormalColor:String;

        public var tabFontSelectedColor:String;

        public var tabFontOverColor:String;

        public var tabFontBold:Boolean;

        public var tabFontItalic:Boolean;

        internal var chat:net.bigpoint.as3chat.Chat;

        internal var gap:int;

        internal var tabMask:flash.display.Sprite;

        internal var maskSet:Boolean;

        internal var maskLength:int;

        internal var count:int=0;

        internal var pos:int=0;

        public static var tabAlphaUnselected:int=100;
    }
}
