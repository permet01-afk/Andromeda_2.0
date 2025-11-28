package net.bigpoint.as3chat 
{
    import flash.display.*;
    import flash.events.*;
    import flash.geom.*;
    import net.bigpoint.flashcorelib.resources.*;
    
    public class ScrollBar extends flash.display.MovieClip
    {
        public function ScrollBar(arg1:net.bigpoint.as3chat.Chat, arg2:int, arg3:int, arg4:int)
        {
            this.sMask = new flash.display.Sprite();
            super();
            this.chat = arg1;
            this.scrollBar_X = arg2;
            this.scrollBar_Y = arg3;
            this.scrollBar_Height = arg4;
            this.init();
            this.addEventListener(flash.events.Event.ENTER_FRAME, this.onEnterFrame);
            return;
        }

        internal function onMouseUpScrollbuttonUp(arg1:flash.events.MouseEvent):void
        {
            this.flag_0 = false;
            return;
        }

        internal function onMouseDownScrollbuttonDown(arg1:flash.events.MouseEvent):void
        {
            this.flag_0 = false;
            this.flag_1 = true;
            return;
        }

        
        {
            SCROLL_STOP = 0;
            SCROLL_UP_FAST = 1;
            SCROLL_UP = 2;
            SCROLL_DOWN = 3;
            SCROLL_DOWN_FAST = 4;
            SCROLL_JUMP_UP = 5;
            SCROLL_JUMP_DOWN = 6;
        }

        internal function onMouseClickDown(arg1:flash.events.MouseEvent):void
        {
            return;
        }

        internal function onMouseUp(arg1:flash.events.MouseEvent):void
        {
            this.chat.startAutoscrollTimer();
            this.dragging = false;
            return;
        }

        internal function onMouseOver(arg1:flash.events.MouseEvent):void
        {
            this.mouseOverScrollbar = true;
            return;
        }

        internal function onMouseOut(arg1:flash.events.MouseEvent):void
        {
            this.mouseOverScrollbar = false;
            return;
        }

        internal function onMouseDown(arg1:flash.events.MouseEvent):void
        {
            this.chat.autoscroll = false;
            this.holder.startDrag(false, this.bounds);
            this.dragging = true;
            return;
        }

        public function _move(arg1:int, arg2:int):void
        {
            if (this.chat.isUseScrollBarBg()) 
            {
                this.graphics.clear();
                this.py = arg2;
                this.bounds.x = this.scrollBar_X + arg1;
                this.bounds.y = this.scrollBar_Y + this.scrollBarUp.height - 5;
                this.bounds.height = this.scrollBar_Height + arg2 - this.scrollBarUp.height - this.scrollBarDown.height - this.holder.height - 10;
                this.holder.x = this.scrollBar_X + arg1;
                this.scrollBarUp.x = this.scrollBar_X + arg1;
                this.scrollBarUp.y = this.scrollBar_Y;
                this.scrollBarBack.x = this.scrollBar_X + arg1;
                this.scrollBarBack.y = this.scrollBar_Y + this.scrollBarUp.height;
                this.scrollBarDown.x = this.scrollBar_X + arg1;
                this.scrollBarDown.y = this.scrollBar_Height + arg2;
                this.graphics.drawRect(this.bounds.x, this.bounds.y, this.scrollBar_Width, this.bounds.height);
                this.scrollBarBack.height = this.scrollBar_Height + arg2 - this.scrollBarUp.height - this.holder.height - 7;
            }
            else 
            {
                this.graphics.clear();
                this.graphics.beginFill(uint(this.chat.getScrollBarShapeColor()), this.chat.getScrollBarAlpha());
                this.graphics.drawRect(this.scrollBar_X + arg1, 35, this.scrollBar_Width, this.scrollBar_Height + arg2);
                this.py = arg2;
                this.bounds.x = this.scrollBar_X + arg1;
                this.bounds.y = this.scrollBar_Y;
                this.bounds.height = this.scrollBar_Height + arg2 - this.holderBitmap.height;
                this.holder.x = this.scrollBar_X + arg1;
            }
            return;
        }

        internal function onEnterFrame(arg1:flash.events.Event):void
        {
            if (this.flag_0) 
            {
                if (this.holder.y > this.bounds.y) 
                {
                    this.holder.y = this.holder.y - 2;
                    this.dragging = true;
                }
                else 
                {
                    this.holder.y = this.bounds.y;
                }
            }
            if (this.flag_1) 
            {
                if (this.holder.y < this.scrollBarDown.y - this.scrollBarDown.height) 
                {
                    this.holder.y = this.holder.y + 2;
                    this.dragging = true;
                }
                else 
                {
                    this.holder.y = this.scrollBarDown.y - this.scrollBarDown.height;
                }
            }
            if (this.dragging) 
            {
                this.repaint();
            }
            else 
            {
                this.scrollDirection = SCROLL_STOP;
            }
            return;
        }

        public function repaint():void
        {
            var loc1:*=this.scrollBar_Height + this.py - this.holder.height;
            var loc2:*=this.holder.y - this.holder.height;
            var loc3:*=loc1 / 4;
            if (loc2 - 7 == 0) 
            {
                this.scrollDirection = SCROLL_JUMP_UP;
            }
            if (loc2 - 7 > 0 && loc2 < loc3) 
            {
                this.scrollDirection = SCROLL_UP_FAST;
            }
            if (loc2 > loc3 && loc2 < loc3 * 2) 
            {
                this.scrollDirection = SCROLL_UP;
            }
            if (loc2 > loc3 * 2 && loc2 < loc3 * 3) 
            {
                this.scrollDirection = SCROLL_DOWN;
            }
            if (loc2 > loc3 * 3 && loc2 - 7 < loc3 * 4) 
            {
                this.scrollDirection = SCROLL_DOWN_FAST;
            }
            if (loc2 - 7 == loc3 * 4) 
            {
                this.scrollDirection = SCROLL_JUMP_DOWN;
            }
            return;
        }

        public function getScrollDirection():int
        {
            return this.scrollDirection;
        }

        public function isMouseOverScrollbar():Boolean
        {
            return this.mouseOverScrollbar;
        }

        public function stopScrolling():void
        {
            this.holder.stopDrag();
            this.dragging = false;
            return;
        }

        public function setHolderToBottom():void
        {
            if (this.chat.isUseScrollBarBg()) 
            {
                this.holder.y = this.scrollBarDown.y - this.scrollBarDown.height;
            }
            else 
            {
                this.holder.y = this.scrollBar_Height + this.py + this.holderBitmap.height / 2 - 7;
            }
            return;
        }

        public function getFlag_0():Boolean
        {
            return this.flag_0;
        }

        public function cleanup():void
        {
            this.removeEventListener(flash.events.Event.ENTER_FRAME, this.onEnterFrame);
            if (this.chat.isUseScrollBarBg()) 
            {
                this.scrollBarUp.removeEventListener(flash.events.MouseEvent.MOUSE_DOWN, this.onMouseDownScrollbuttonUp);
                this.scrollBarUp.removeEventListener(flash.events.MouseEvent.MOUSE_UP, this.onMouseUpScrollbuttonUp);
                this.scrollBarDown.removeEventListener(flash.events.MouseEvent.MOUSE_DOWN, this.onMouseDownScrollbuttonDown);
                this.scrollBarDown.removeEventListener(flash.events.MouseEvent.MOUSE_UP, this.onMouseUpScrollbuttonDown);
                this.scrollBarBack.removeEventListener(flash.events.MouseEvent.CLICK, this.onMouseClickDown);
            }
            this.holder.removeEventListener(flash.events.MouseEvent.MOUSE_DOWN, this.onMouseDown);
            this.holder.removeEventListener(flash.events.MouseEvent.MOUSE_UP, this.onMouseUp);
            this.removeEventListener(flash.events.MouseEvent.MOUSE_OVER, this.onMouseOver);
            this.removeEventListener(flash.events.MouseEvent.MOUSE_OUT, this.onMouseOut);
            return;
        }

        internal function onMouseUpScrollbuttonDown(arg1:flash.events.MouseEvent):void
        {
            this.flag_1 = false;
            return;
        }

        internal function init():void
        {
            this.buttonMode = true;
            this.holderBitmap = net.bigpoint.flashcorelib.resources.MediaHandler.getInstance().getBitmap("holder");
            if (this.chat.isUseScrollBarBg()) 
            {
                this.scrollBarUp = new flash.display.MovieClip();
                this.scrollBarUp.addChild(net.bigpoint.flashcorelib.resources.MediaHandler.getInstance().getBitmap("scrollBarUp"));
                this.scrollBarUp.addEventListener(flash.events.MouseEvent.MOUSE_DOWN, this.onMouseDownScrollbuttonUp);
                this.scrollBarUp.addEventListener(flash.events.MouseEvent.MOUSE_UP, this.onMouseUpScrollbuttonUp);
                this.scrollBarDown = new flash.display.MovieClip();
                this.scrollBarDown.addChild(net.bigpoint.flashcorelib.resources.MediaHandler.getInstance().getBitmap("scrollBarDown"));
                this.scrollBarDown.addEventListener(flash.events.MouseEvent.MOUSE_DOWN, this.onMouseDownScrollbuttonDown);
                this.scrollBarDown.addEventListener(flash.events.MouseEvent.MOUSE_UP, this.onMouseUpScrollbuttonDown);
                this.scrollBarBack = new flash.display.MovieClip();
                this.scrollBarBack.addChild(net.bigpoint.flashcorelib.resources.MediaHandler.getInstance().getBitmap("scrollBarBack"));
                this.scrollBarBack.addEventListener(flash.events.MouseEvent.CLICK, this.onMouseClickDown);
                this.addChild(this.scrollBarUp);
                this.addChild(this.scrollBarDown);
                this.addChild(this.scrollBarBack);
            }
            this.scrollBar_Width = this.holderBitmap.width;
            this.bounds = new flash.geom.Rectangle(this.scrollBar_X, this.scrollBar_Y, 0, this.scrollBar_Height - this.holderBitmap.height);
            this.holder = new flash.display.Sprite();
            this.holder.addChild(this.holderBitmap);
            this.holder.x = this.scrollBar_X;
            this.holder.y = this.scrollBar_Y;
            this.addChild(this.holder);
            this.holder.addEventListener(flash.events.MouseEvent.MOUSE_DOWN, this.onMouseDown);
            this.holder.addEventListener(flash.events.MouseEvent.MOUSE_UP, this.onMouseUp);
            this.addEventListener(flash.events.MouseEvent.MOUSE_OVER, this.onMouseOver);
            this.addEventListener(flash.events.MouseEvent.MOUSE_OUT, this.onMouseOut);
            return;
        }

        internal function onMouseDownScrollbuttonUp(arg1:flash.events.MouseEvent):void
        {
            this.flag_0 = true;
            this.flag_1 = false;
            return;
        }

        internal var scrollBar_X:int;

        internal var scrollBar_Y:int;

        internal var scrollBar_Width:int;

        internal var scrollBar_Height:int;

        internal var bounds:flash.geom.Rectangle;

        internal var holderBitmap:flash.display.Bitmap;

        internal var dragging:Boolean;

        internal var py:int;

        internal var scrollBarUp:flash.display.MovieClip;

        internal var scrollBarDown:flash.display.MovieClip;

        internal var scrollBarBack:flash.display.MovieClip;

        internal var sMask:flash.display.Sprite;

        internal var scrollDirection:int;

        internal var chat:net.bigpoint.as3chat.Chat;

        internal var mouseOverScrollbar:Boolean;

        internal var flag_0:Boolean=false;

        internal var flag_1:Boolean=false;

        public static var SCROLL_STOP:int=0;

        public static var SCROLL_UP_FAST:int=1;

        internal var holder:flash.display.Sprite;

        public static var SCROLL_DOWN:int=3;

        public static var SCROLL_DOWN_FAST:int=4;

        public static var SCROLL_JUMP_UP:int=5;

        public static var SCROLL_JUMP_DOWN:int=6;

        public static var SCROLL_UP:int=2;
    }
}
