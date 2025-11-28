package net.bigpoint.as3chat 
{
    import flash.display.*;
    import flash.geom.*;
    import net.bigpoint.flashcorelib.resources.*;
    
    public class SuperBitmap extends flash.display.Bitmap
    {
        public function SuperBitmap(arg1:net.bigpoint.as3chat.Chat, arg2:Boolean, arg3:flash.display.Bitmap, arg4:flash.display.Bitmap, arg5:flash.display.Bitmap, arg6:flash.display.Bitmap, arg7:flash.display.Bitmap, arg8:flash.display.Bitmap, arg9:flash.display.Bitmap, arg10:flash.display.Bitmap, arg11:flash.display.Bitmap)
        {
            super();
            this.chat = arg1;
            this.tiled = arg2;
            this.upLeft = arg3;
            this.upMid = arg4;
            this.upRight = arg5;
            this.midLeft = arg6;
            this.midMid = arg7;
            this.midRight = arg8;
            this.downLeft = arg9;
            this.downMid = arg10;
            this.downRight = arg11;
            this.resizerPosition = new flash.geom.Point();
            this.rect = new flash.geom.Rectangle();
            this.point = new flash.geom.Point();
            this.maxWidth = int(net.bigpoint.flashcorelib.resources.SettingsHandler.getInstance().getVariable("maxWidth") as String);
            this.maxHeight = int(net.bigpoint.flashcorelib.resources.SettingsHandler.getInstance().getVariable("maxHeight") as String);
            this.minWidth = int(net.bigpoint.flashcorelib.resources.SettingsHandler.getInstance().getVariable("minWidth") as String);
            this.minHeight = int(net.bigpoint.flashcorelib.resources.SettingsHandler.getInstance().getVariable("minHeight") as String);
            this.initialWidth = this.minWidth;
            this.initialHeight = this.minHeight;
            this.init();
            this.repaint();
            return;
        }

        public function getMinHeight():int
        {
            return this.minHeight;
        }

        internal function setRectangle(arg1:int, arg2:int, arg3:int, arg4:int):void
        {
            this.rect.x = arg1;
            this.rect.y = arg2;
            this.rect.width = arg3;
            this.rect.height = arg4;
            return;
        }

        internal function init():void
        {
            var loc1:*=0;
            var loc2:*=0;
            var loc3:*=NaN;
            var loc4:*=NaN;
            if (this.tiled) 
            {
                this.pattern = new flash.display.Bitmap(new flash.display.BitmapData(this.maxWidth, this.maxHeight, true, 16777215));
                loc1 = this.maxWidth / this.midMid.width + 1;
                loc2 = this.maxHeight / this.midMid.height + 1;
                this.setRectangle(0, 0, this.midMid.width, this.midMid.height);
                loc3 = 0;
                while (loc3 < loc2) 
                {
                    loc4 = 0;
                    while (loc4 < loc1) 
                    {
                        this.setPoint(loc4 * this.midMid.width, loc3 * this.midMid.height);
                        this.pattern.bitmapData.copyPixels(this.midMid.bitmapData, this.rect, this.point);
                        ++loc4;
                    }
                    ++loc3;
                }
                this.midMid = this.pattern;
            }
            this.bitmapData = new flash.display.BitmapData(this.maxWidth, this.maxHeight, true, 16777215);
            return;
        }

        internal function setPoint(arg1:int, arg2:int):void
        {
            this.point.x = arg1;
            this.point.y = arg2;
            return;
        }

        public function repaint():void
        {
            this.midWidth = this.minWidth - this.upLeft.width - this.upRight.width;
            this.midHeight = this.minHeight - this.upMid.height - this.downMid.height;
            this.setRectangle(0, 0, this.maxWidth, this.maxHeight);
            this.bitmapData.fillRect(this.rect, 16777215);
            this.setRectangle(0, 0, this.upLeft.width, this.upLeft.height);
            this.setPoint(0, 0);
            this.bitmapData.copyPixels(this.upLeft.bitmapData, this.rect, this.point);
            this.setRectangle(0, 0, this.midWidth, this.upMid.height);
            this.setPoint(this.upLeft.width, 0);
            this.bitmapData.copyPixels(this.upMid.bitmapData, this.rect, this.point);
            this.setRectangle(0, 0, this.upRight.width, this.upRight.height);
            this.setPoint(this.upLeft.width + this.midWidth, 0);
            this.bitmapData.copyPixels(this.upRight.bitmapData, this.rect, this.point);
            this.setRectangle(0, 0, this.midLeft.width, this.midHeight);
            this.setPoint(0, this.upLeft.height);
            this.bitmapData.copyPixels(this.midLeft.bitmapData, this.rect, this.point);
            this.setRectangle(0, 0, this.midWidth, this.midHeight);
            this.setPoint(this.midLeft.width, this.upMid.height);
            this.bitmapData.copyPixels(this.midMid.bitmapData, this.rect, this.point);
            this.setRectangle(0, 0, this.midRight.width, this.midHeight);
            this.setPoint(this.midLeft.width + this.midWidth, this.upRight.height);
            this.bitmapData.copyPixels(this.midRight.bitmapData, this.rect, this.point);
            this.setRectangle(0, 0, this.downLeft.width, this.downLeft.height);
            this.setPoint(0, this.upLeft.height + this.midHeight);
            this.bitmapData.copyPixels(this.downLeft.bitmapData, this.rect, this.point);
            this.setRectangle(0, 0, this.midWidth, this.downMid.height);
            this.setPoint(this.downLeft.width, this.upMid.height + this.midHeight);
            this.bitmapData.copyPixels(this.downMid.bitmapData, this.rect, this.point);
            this.setRectangle(0, 0, this.downRight.width, this.downRight.height);
            this.setPoint(this.downLeft.width + this.midWidth, this.upRight.height + this.midHeight);
            this.bitmapData.copyPixels(this.downRight.bitmapData, this.rect, this.point);
            return;
        }

        public function getResizerPosition():flash.geom.Point
        {
            this.resizerPosition.x = this.downLeft.width + this.midWidth;
            this.resizerPosition.y = this.upRight.height + this.midHeight;
            if (this.resizerInitialPosition == null) 
            {
                this.resizerInitialPosition = new flash.geom.Point();
                this.resizerInitialPosition.x = this.resizerPosition.x;
                this.resizerInitialPosition.y = this.resizerPosition.y;
            }
            return this.resizerPosition;
        }

        public function setResizerWidth(arg1:int):void
        {
            this.resizerPosition.x = arg1;
            this.repaint();
            return;
        }

        public function getInitialHookPosition():flash.geom.Point
        {
            return this.resizerInitialPosition;
        }

        public function getTotalWidth():int
        {
            return this.minWidth;
        }

        public function setTotalWidth(arg1:int):void
        {
            this.minWidth = arg1;
            this.repaint();
            return;
        }

        public function setTotalHeight(arg1:int):void
        {
            this.minHeight = arg1;
            this.repaint();
            return;
        }

        public function getTotalHeight():int
        {
            return this.minHeight;
        }

        public function getMinWidth():int
        {
            return this.minWidth;
        }

        public function getInitialWidth():int
        {
            return this.initialWidth;
        }

        public function getInitialHeight():int
        {
            return this.initialHeight;
        }

        public function getMaxWidth():int
        {
            return this.maxWidth;
        }

        public function getMaxHeight():int
        {
            return this.maxHeight;
        }

        public function getUpMid():flash.display.Bitmap
        {
            return this.upMid;
        }

        internal var point:flash.geom.Point;

        internal var midWidth:int;

        internal var midMid:flash.display.Bitmap;

        internal var pattern:flash.display.Bitmap;

        internal var tiled:Boolean;

        internal var chat:net.bigpoint.as3chat.Chat;

        internal var upLeft:flash.display.Bitmap;

        internal var midHeight:int;

        internal var upMid:flash.display.Bitmap;

        internal var upRight:flash.display.Bitmap;

        internal var midLeft:flash.display.Bitmap;

        internal var midRight:flash.display.Bitmap;

        internal var downLeft:flash.display.Bitmap;

        internal var downMid:flash.display.Bitmap;

        internal var downRight:flash.display.Bitmap;

        internal var resizerInitialPosition:flash.geom.Point;

        internal var resizerPosition:flash.geom.Point;

        internal var initialWidth:int;

        internal var maxWidth:int;

        internal var initialHeight:int;

        internal var minWidth:int;

        internal var minHeight:int;

        internal var maxHeight:int;

        internal var rect:flash.geom.Rectangle;
    }
}
