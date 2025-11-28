package net.bigpoint.as3chat 
{
    import flash.display.*;
    import flash.events.*;
    import flash.text.*;
    import net.bigpoint.flashcorelib.resources.*;
    
    public class TabElement extends flash.display.MovieClip
    {
        public function TabElement(arg1:net.bigpoint.as3chat.MyTabBar, arg2:flash.display.BitmapData, arg3:int, arg4:String, arg5:String, arg6:int)
        {
            this.textField = new flash.text.TextField();
            super();
            this.tabbar = arg1;
            this.roomName = arg5;
            this.bgImage = new flash.display.Bitmap(arg2);
            this.roomId = arg3;
            this.roomType = arg6;
            this.tabName = arg4;
            this.addChild(this.bgImage);
            this.alpha = net.bigpoint.as3chat.MyTabBar.tabAlphaUnselected / 100;
            this.textField.selectable = false;
            this.textField.mouseEnabled = false;
            this.textField.height = 2 * this.bgImage.height;
            this.textField.wordWrap = false;
            this.textField.y = -1;
            var loc1:*;
            if ((loc1 = net.bigpoint.flashcorelib.resources.SettingsHandler.getInstance().getVariable("tabBar_textfield_YPos") as String) == null) 
            {
                this.textField.y = -1;
            }
            else 
            {
                this.textField.y = parseInt(loc1);
            }
            var loc2:*;
            (loc2 = new flash.text.TextFormat()).align = flash.text.TextFormatAlign.CENTER;
            this.textField.defaultTextFormat = loc2;
            this.textField.antiAliasType = flash.text.AntiAliasType.ADVANCED;
            this.setText(arg1.tabFontNormalColor);
            this.buttonMode = true;
            this.addChild(this.textField);
            this.addEventListener(flash.events.MouseEvent.CLICK, this.onMouseClick);
            this.addEventListener(flash.events.MouseEvent.MOUSE_OVER, this.onMouseOver);
            this.addEventListener(flash.events.MouseEvent.MOUSE_OUT, this.onMouseOut);
            return;
        }

        internal function setText(arg1:String):void
        {
            var loc1:*="<FONT FACE=\'" + this.tabbar.tabFontFace + "\' SIZE=\'" + this.tabbar.tabFontSize + "\' COLOR=\'" + arg1 + "\'>";
            if (this.tabbar.tabFontBold) 
            {
                loc1 = loc1 + "<B>";
            }
            if (this.tabbar.tabFontItalic) 
            {
                loc1 = loc1 + "<I>";
            }
            loc1 = loc1 + net.bigpoint.as3chat.Main.maskHTML(this.tabName);
            if (this.tabbar.tabFontItalic) 
            {
                loc1 = loc1 + "</I>";
            }
            if (this.tabbar.tabFontBold) 
            {
                loc1 = loc1 + "</B>";
            }
            loc1 = loc1 + "</FONT>";
            this.textField.htmlText = loc1;
            return;
        }

        internal function onMouseClick(arg1:flash.events.MouseEvent):void
        {
            this.tabbar.tabClick(this.roomId);
            return;
        }

        internal function onMouseOver(arg1:flash.events.MouseEvent):void
        {
            if (!this.selected) 
            {
                this.setText(this.tabbar.tabFontOverColor);
            }
            return;
        }

        internal function onMouseOut(arg1:flash.events.MouseEvent):void
        {
            if (!this.selected) 
            {
                this.setText(this.tabbar.tabFontNormalColor);
            }
            return;
        }

        public function setSelected(arg1:Boolean):void
        {
            if (arg1) 
            {
                this.setText(this.tabbar.tabFontSelectedColor);
                this.alpha = 1;
            }
            else 
            {
                this.setText(this.tabbar.tabFontNormalColor);
                this.alpha = net.bigpoint.as3chat.MyTabBar.tabAlphaUnselected / 100;
            }
            this.selected = arg1;
            return;
        }

        public function getBgImage():flash.display.Bitmap
        {
            return this.bgImage;
        }

        public function getRoomId():int
        {
            return this.roomId;
        }

        public function getRoomName():String
        {
            return this.roomName;
        }

        public function getRoomType():int
        {
            return this.roomType;
        }

        public function setRoomType(arg1:int):void
        {
            this.roomType = arg1;
            return;
        }

        public function isSelected():Boolean
        {
            return this.selected;
        }

        public function getTabWidth():int
        {
            return this.bgImage.width;
        }

        public function getTextField():flash.text.TextField
        {
            return this.textField;
        }

        public function cleanup():void
        {
            this.removeEventListener(flash.events.MouseEvent.CLICK, this.onMouseClick);
            this.removeEventListener(flash.events.MouseEvent.MOUSE_OVER, this.onMouseOver);
            this.removeEventListener(flash.events.MouseEvent.MOUSE_OUT, this.onMouseOut);
            return;
        }

        internal var bgImage:flash.display.Bitmap;

        internal var roomId:int;

        internal var tabName:String;

        internal var roomName:String;

        internal var roomType:int;

        internal var tabbar:net.bigpoint.as3chat.MyTabBar;

        internal var textField:flash.text.TextField;

        internal var selected:Boolean;
    }
}
