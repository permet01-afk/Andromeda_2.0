package net.bigpoint.as3chat 
{
    import fl.controls.*;
    import flash.display.*;
    import flash.events.*;
    import flash.geom.*;
    import flash.text.*;
    import flash.ui.*;
    import flash.utils.*;
    import mx.utils.*;
    import net.bigpoint.flashcorelib.resources.*;
    
    public class Chat extends flash.display.MovieClip
    {
        public function Chat(arg1:net.bigpoint.as3chat.Main, arg2:int, arg3:String=null)
        {
            this.history = new Array();
            this.joinedRoomIds = new Array();
            this.viewMode = VIEW_NORMAL;
            super();
            this.main = arg1;
            this.mode = arg2;
            this.errorMsg = arg3;
            this.addEventListener(flash.events.Event.ENTER_FRAME, this._onEnterFrame);
            this.addEventListener(flash.events.MouseEvent.MOUSE_MOVE, this.onMouseMove);
            this.addEventListener(flash.events.MouseEvent.MOUSE_OUT, this.onMouseOutResizer);
            this.autoscrollCnt = this.autoscrollInitialCnt;
            this.scrollTimer = new flash.utils.Timer(100, 0);
            this.scrollTimer.addEventListener(flash.events.TimerEvent.TIMER, this.onTimerTick);
            this.scrollTimer.start();
            this.spamTimer = new flash.utils.Timer(1000, 0);
            this.spamTimer.addEventListener(flash.events.TimerEvent.TIMER, this.reduceSpamCounter);
            this.spamTimer.start();
            this.init();
            this.setMask();
            this.repaint();
            return;
        }

        internal function reduceSpamCounter(arg1:flash.events.TimerEvent):void
        {
            if (this.spamCounter > 0) 
            {
                var loc1:*;
                var loc2:*=((loc1 = this).spamCounter - 1);
                loc1.spamCounter = loc2;
            }
            return;
        }

        public function getResizeArrow():flash.display.MovieClip
        {
            return this.resize_arrow;
        }

        internal function _onEnterFrame(arg1:flash.events.Event):void
        {
            if (this.resizing) 
            {
                this.repaint();
                this.textOutput.scrollV = this.textOutput.maxScrollV;
                this.myScrollBar.setHolderToBottom();
                this.updateButtonsPositions();
            }
            return;
        }

        public function updateButtonsPositions():void
        {
            this.btnRight.x = this.tabBar.getTabMask().width + 23;
            this.btnRight.y = parseInt(this.settingsHandler.getVariable("tabBar_YPos") as String);
            this.btnLeft.x = 6;
            this.btnLeft.y = parseInt(this.settingsHandler.getVariable("tabBar_YPos") as String);
            if (this.btnMinimizeVisible) 
            {
                this.btnMinimize.x = this.btnRight.x + this.btnRight.width + 2;
                this.btnMinimize.y = parseInt(this.settingsHandler.getVariable("tabBar_YPos") as String);
                if (this.btnRestoreVisible) 
                {
                    this.btnRestore.x = parseInt(this.settingsHandler.getVariable("restoreButton_XPos") as String);
                    this.btnRestore.y = parseInt(this.settingsHandler.getVariable("restoreButton_YPos") as String);
                }
            }
            return;
        }

        public function init():void
        {
            var loc5:*=null;
            this.mediaHandler = net.bigpoint.flashcorelib.resources.MediaHandler.getInstance();
            this.settingsHandler = net.bigpoint.flashcorelib.resources.SettingsHandler.getInstance();
            this.languageHandler = net.bigpoint.flashcorelib.resources.LanguageHandler.getInstance();
            if (String(this.settingsHandler.getVariable("minWidth")) == String(this.settingsHandler.getVariable("maxWidth")) && String(this.settingsHandler.getVariable("minHeight")) == String(this.settingsHandler.getVariable("maxHeight"))) 
            {
                this.resizable = false;
            }
            this.tabVisible = this.parseBoolean(this.settingsHandler.getVariable("tabVisible") as String);
            this.chatFontFace = String(this.settingsHandler.getVariable("chatFontFace"));
            this.chatFontSize = int(this.settingsHandler.getVariable("chatFontSize"));
            this.chatFontColor = String(this.settingsHandler.getVariable("chatFontColor"));
            this.systemFontColor = String(this.settingsHandler.getVariable("systemFontColor"));
            this.adminFontColor = String(this.settingsHandler.getVariable("adminFontColor"));
            this.whisperFontColor = String(this.settingsHandler.getVariable("whisperFontColor"));
            this.scrollBarShapeColor = this.settingsHandler.getVariable("scrollBarShapeColor") as String;
            this.scrollBarAlpha = parseInt(this.settingsHandler.getVariable("scrollBarAlpha") as String);
            this.draggable = this.parseBoolean(this.settingsHandler.getVariable("draggable") as String);
            this.btnMinimizeVisible = this.parseBoolean(this.settingsHandler.getVariable("btnMinimizeVisible") as String);
            this.btnRestoreVisible = this.parseBoolean(this.settingsHandler.getVariable("btnRestoreVisible") as String);
            this.useScrollBarBg = this.parseBoolean(this.settingsHandler.getVariable("useScrollBarBg") as String);
            this.resizerVisible = this.parseBoolean(this.settingsHandler.getVariable("resizerVisible") as String);
            this.downRight = this.mediaHandler.getBitmap("downRight");
            if (this.resizerVisible) 
            {
                this.resizer = new flash.display.Sprite();
                this.resizer.graphics.beginFill(16763904);
                this.resizer.graphics.drawRect(0, 0, this.downRight.width, this.downRight.height);
                this.resizer.alpha = 0;
            }
            this.superBitmap = new net.bigpoint.as3chat.SuperBitmap(this, this.parseBoolean(this.settingsHandler.getVariable("backgroundTiled") as String), this.mediaHandler.getBitmap("upLeft"), this.mediaHandler.getBitmap("upMid"), this.mediaHandler.getBitmap("upRight"), this.mediaHandler.getBitmap("midLeft"), this.mediaHandler.getBitmap("midMid"), this.mediaHandler.getBitmap("midRight"), this.mediaHandler.getBitmap("downLeft"), this.mediaHandler.getBitmap("downMid"), this.mediaHandler.getBitmap("downRight"));
            this.tabMaskInitWidth = parseInt(String(this.settingsHandler.getVariable("minWidth"))) - 60;
            this.addEventListener(flash.events.MouseEvent.MOUSE_DOWN, this.onMouseDown);
            this.main.stage.addEventListener(flash.events.MouseEvent.MOUSE_UP, this.onMouseUp);
            this.addEventListener(flash.events.MouseEvent.MOUSE_UP, this.onMouseUp);
            if (this.resizerVisible) 
            {
                loc5 = this.superBitmap.getResizerPosition();
                this.resizer.x = loc5.x;
                this.resizer.y = loc5.y;
                this.hookRectangle = new flash.geom.Rectangle(this.superBitmap.getInitialHookPosition().x, this.superBitmap.getInitialHookPosition().y, this.superBitmap.getMaxWidth() - this.superBitmap.getInitialHookPosition().x - this.resizer.width, this.superBitmap.getMaxHeight() - this.superBitmap.getInitialHookPosition().y - this.resizer.height);
                this.resize_arrow = new flash.display.MovieClip();
                this.resize_arrow.addChild(net.bigpoint.flashcorelib.resources.MediaHandler.getInstance().getBitmap("resize_arrow"));
                this.resize_arrow.visible = false;
            }
            this.textOutput = new flash.text.TextField();
            this.textOutput.addEventListener(flash.events.TextEvent.LINK, this.linkEvent);
            this.textOutput.border = this.parseBoolean(this.settingsHandler.getVariable("OutputField_Border") as String);
            this.textOutput.styleSheet = this.main.getCss();
            this.textOutput.multiline = true;
            this.textOutput.wordWrap = true;
            this.textOutput.addEventListener(flash.events.MouseEvent.MOUSE_OVER, this.onMouseOverTextOutput);
            this.textOutput.addEventListener(flash.events.MouseEvent.MOUSE_OUT, this.onMouseOutTextOutput);
            this.textInput = new fl.controls.TextInput();
			var emptySkin:Sprite = new Sprite();
			this.textInput.setStyle("upSkin", emptySkin);
			this.textInput.setStyle("focusRectSkin", emptySkin);
            this.textInput.textField.background = false;
            this.textInput.textField.border = this.parseBoolean(this.settingsHandler.getVariable("InputField_Border") as String);
            this.textInput.addEventListener(flash.events.MouseEvent.MOUSE_OVER, this.onMouseOverInput);
            this.textInput.addEventListener(flash.events.MouseEvent.MOUSE_OUT, this.onMouseOutInput);
            var loc1:*=new flash.text.TextFormat();
            loc1.font = this.settingsHandler.getVariable("InputField_Font") as String;
            loc1.size = parseInt(this.settingsHandler.getVariable("InputField_FontSize") as String);
            loc1.color = uint(this.settingsHandler.getVariable("InputField_FontColor") as String);
            this.textInput.setStyle("textFormat", loc1);
            this.tabBar = new net.bigpoint.as3chat.MyTabBar(this, this.mediaHandler.getBitmap("tab"), this.tabMaskInitWidth);
            this.tabBar.x = parseInt(this.settingsHandler.getVariable("tabBar_XPos") as String);
            this.tabBar.y = parseInt(this.settingsHandler.getVariable("tabBar_YPos") as String);
            this.btnMaximize = new flash.display.SimpleButton(this.mediaHandler.getBitmap("btn_maximize_normal"), this.mediaHandler.getBitmap("btn_maximize_hover"), this.mediaHandler.getBitmap("btn_maximize_pressed"), this.mediaHandler.getBitmap("btn_maximize_hover"));
            this.initialMaximizeXPos = parseInt(this.settingsHandler.getVariable("maximizeButton_XPos") as String);
            this.btnMaximize.x = this.initialMaximizeXPos;
            this.btnMaximize.y = parseInt(this.settingsHandler.getVariable("maximizeButton_YPos") as String);
            this.btnRight = new flash.display.MovieClip();
            this.btnRight.addChild(net.bigpoint.flashcorelib.resources.MediaHandler.getInstance().getBitmap("btn_right_normal"));
            this.btnRight.addEventListener(flash.events.MouseEvent.CLICK, this.onBtnRightClick);
            this.btnRight.addEventListener(flash.events.MouseEvent.MOUSE_OVER, this.onBtnRightOver);
            this.btnRight.addEventListener(flash.events.MouseEvent.MOUSE_OUT, this.onBtnRightOut);
            this.btnRight.buttonMode = true;
            this.btnLeft = new flash.display.MovieClip();
            this.btnLeft.addChild(net.bigpoint.flashcorelib.resources.MediaHandler.getInstance().getBitmap("btn_left_normal"));
            this.btnLeft.addEventListener(flash.events.MouseEvent.CLICK, this.onBtnLeftClick);
            this.btnLeft.addEventListener(flash.events.MouseEvent.MOUSE_OVER, this.onBtnLeftOver);
            this.btnLeft.addEventListener(flash.events.MouseEvent.MOUSE_OUT, this.onBtnLeftOut);
            this.btnLeft.buttonMode = true;
            if (this.btnMinimizeVisible) 
            {
                this.btnMinimize = new flash.display.MovieClip();
                this.btnMinimize.addChild(net.bigpoint.flashcorelib.resources.MediaHandler.getInstance().getBitmap("btn_minimize_normal"));
                this.btnMinimize.addEventListener(flash.events.MouseEvent.CLICK, this.onBtnMinimizeClick);
                this.btnMinimize.addEventListener(flash.events.MouseEvent.MOUSE_OVER, this.onBtnMinimizeOver);
                this.btnMinimize.addEventListener(flash.events.MouseEvent.MOUSE_OUT, this.onBtnMinimizeOut);
                this.btnMinimize.buttonMode = true;
                if (this.btnRestoreVisible) 
                {
                    this.btnRestore = new flash.display.MovieClip();
                    this.btnRestore.addChild(net.bigpoint.flashcorelib.resources.MediaHandler.getInstance().getBitmap("btn_restore_normal"));
                    this.btnRestore.addEventListener(flash.events.MouseEvent.CLICK, this.onBtnRestoreClick);
                    this.btnRestore.addEventListener(flash.events.MouseEvent.MOUSE_OVER, this.onBtnRestoreOver);
                    this.btnRestore.addEventListener(flash.events.MouseEvent.MOUSE_OUT, this.onBtnRestoreOut);
                    this.btnRestore.buttonMode = true;
                }
            }
            var loc2:*=0;
            this.addChildAt(this.superBitmap, loc2++);
            if (this.tabVisible) 
            {
                this.addChildAt(this.tabBar, loc2++);
            }
            this.addChildAt(this.textOutput, loc2++);
            this.addChildAt(this.textInput, loc2++);
            this.addChildAt(this.btnRight, loc2);
            this.addChildAt(this.btnLeft, loc2);
            if (this.btnMinimizeVisible) 
            {
                this.addChildAt(this.btnMinimize, loc2);
            }
            this.btnMaximize.addEventListener(flash.events.MouseEvent.CLICK, this.toggleView);
            this.addEventListener(flash.events.KeyboardEvent.KEY_DOWN, this.onKeyDown);
            this.textOutput.width = parseInt(this.settingsHandler.getVariable("OutputField_Width") as String);
            this.textOutput.height = parseInt(this.settingsHandler.getVariable("OutputField_Height") as String);
            this.textOutput.x = parseInt(this.settingsHandler.getVariable("OutputField_XPos") as String);
            this.textOutput.y = parseInt(this.settingsHandler.getVariable("OutputField_YPos") as String);
            this.textInput.setSize(parseInt(this.settingsHandler.getVariable("InputField_Width") as String), parseInt(this.settingsHandler.getVariable("InputField_Height") as String));
            this.textInput.move(parseInt(this.settingsHandler.getVariable("InputField_XPos") as String), parseInt(this.settingsHandler.getVariable("InputField_YPos") as String));
            this.textOutputInitWidth = this.textOutput.width;
            this.textOutputInitHeight = this.textOutput.height;
            this.textInputInitWidth = this.textInput.width;
            this.textInputInitY = this.textInput.y;
            if (this.resizerVisible) 
            {
                this.resizer.buttonMode = true;
                this.resizer.addEventListener(flash.events.MouseEvent.MOUSE_DOWN, this.onMouseDownResizer);
                this.resizer.addEventListener(flash.events.MouseEvent.MOUSE_OVER, this.onMouseOverResizer);
                this.resizer.addEventListener(flash.events.MouseEvent.MOUSE_OUT, this.onMouseOutResizer);
                this.resizer.x = this.superBitmap.getResizerPosition().x;
                this.resizer.y = this.superBitmap.getResizerPosition().y;
                this.addChildAt(this.resizer, 3);
            }
            this.main.stage.addEventListener(flash.events.MouseEvent.MOUSE_UP, this.onMouseUp);
            this.myScrollBar = new net.bigpoint.as3chat.ScrollBar(this, parseInt(this.settingsHandler.getVariable("scrollBar_X") as String), parseInt(this.settingsHandler.getVariable("scrollBar_Y") as String), parseInt(this.settingsHandler.getVariable("scrollBar_Height") as String));
            var loc3:*=this.main.getChatWidth();
            if (loc3 != 0) 
            {
                if (this.resizerVisible) 
                {
                    this.resizer.x = loc3;
                }
            }
            var loc4:*;
            if ((loc4 = this.main.getChatHeight()) != 0) 
            {
                if (this.resizerVisible) 
                {
                    this.resizer.y = loc4;
                }
            }
            return;
        }

        internal function onBtnRightOut(arg1:flash.events.MouseEvent):void
        {
            if (this.btnRight.contains(net.bigpoint.flashcorelib.resources.MediaHandler.getInstance().getBitmap("btn_right_over"))) 
            {
                this.btnRight.removeChild(net.bigpoint.flashcorelib.resources.MediaHandler.getInstance().getBitmap("btn_right_over"));
            }
            this.btnRight.addChild(net.bigpoint.flashcorelib.resources.MediaHandler.getInstance().getBitmap("btn_right_normal"));
            return;
        }

        internal function onBtnRightOver(arg1:flash.events.MouseEvent):void
        {
            if (this.btnRight.contains(net.bigpoint.flashcorelib.resources.MediaHandler.getInstance().getBitmap("btn_right_normal"))) 
            {
                this.btnRight.removeChild(net.bigpoint.flashcorelib.resources.MediaHandler.getInstance().getBitmap("btn_right_normal"));
            }
            this.btnRight.addChild(net.bigpoint.flashcorelib.resources.MediaHandler.getInstance().getBitmap("btn_right_over"));
            return;
        }

        internal function onBtnMinimizeOut(arg1:flash.events.MouseEvent):void
        {
            if (this.btnMinimize.contains(net.bigpoint.flashcorelib.resources.MediaHandler.getInstance().getBitmap("btn_minimize_over"))) 
            {
                this.btnMinimize.removeChild(net.bigpoint.flashcorelib.resources.MediaHandler.getInstance().getBitmap("btn_minimize_over"));
            }
            this.btnMinimize.addChild(net.bigpoint.flashcorelib.resources.MediaHandler.getInstance().getBitmap("btn_minimize_normal"));
            return;
        }

        internal function onBtnMinimizeOver(arg1:flash.events.MouseEvent):void
        {
            if (this.btnMinimize.contains(net.bigpoint.flashcorelib.resources.MediaHandler.getInstance().getBitmap("btn_minimize_normal"))) 
            {
                this.btnMinimize.removeChild(net.bigpoint.flashcorelib.resources.MediaHandler.getInstance().getBitmap("btn_minimize_normal"));
            }
            this.btnMinimize.addChild(net.bigpoint.flashcorelib.resources.MediaHandler.getInstance().getBitmap("btn_minimize_over"));
            return;
        }

        internal function onBtnRestoreOut(arg1:flash.events.MouseEvent):void
        {
            if (this.btnRestore.contains(net.bigpoint.flashcorelib.resources.MediaHandler.getInstance().getBitmap("btn_restore_over"))) 
            {
                this.btnRestore.removeChild(net.bigpoint.flashcorelib.resources.MediaHandler.getInstance().getBitmap("btn_restore_over"));
            }
            this.btnRestore.addChild(net.bigpoint.flashcorelib.resources.MediaHandler.getInstance().getBitmap("btn_restore_normal"));
            return;
        }

        internal function onBtnRestoreOver(arg1:flash.events.MouseEvent):void
        {
            if (this.btnRestore.contains(net.bigpoint.flashcorelib.resources.MediaHandler.getInstance().getBitmap("btn_restore_normal"))) 
            {
                this.btnRestore.removeChild(net.bigpoint.flashcorelib.resources.MediaHandler.getInstance().getBitmap("btn_restore_normal"));
            }
            this.btnRestore.addChild(net.bigpoint.flashcorelib.resources.MediaHandler.getInstance().getBitmap("btn_restore_over"));
            return;
        }

        internal function onBtnLeftOut(arg1:flash.events.MouseEvent):void
        {
            if (this.btnLeft.contains(net.bigpoint.flashcorelib.resources.MediaHandler.getInstance().getBitmap("btn_left_over"))) 
            {
                this.btnLeft.removeChild(net.bigpoint.flashcorelib.resources.MediaHandler.getInstance().getBitmap("btn_left_over"));
            }
            this.btnLeft.addChild(net.bigpoint.flashcorelib.resources.MediaHandler.getInstance().getBitmap("btn_left_normal"));
            return;
        }

        internal function onBtnLeftOver(arg1:flash.events.MouseEvent):void
        {
            if (this.btnLeft.contains(net.bigpoint.flashcorelib.resources.MediaHandler.getInstance().getBitmap("btn_left_normal"))) 
            {
                this.btnLeft.removeChild(net.bigpoint.flashcorelib.resources.MediaHandler.getInstance().getBitmap("btn_left_normal"));
            }
            this.btnLeft.addChild(net.bigpoint.flashcorelib.resources.MediaHandler.getInstance().getBitmap("btn_left_over"));
            return;
        }

        internal function onBtnRightClick(arg1:flash.events.MouseEvent):void
        {
            this.tabBar.scrollToRight();
            this.checkScrollButtons();
            return;
        }

        internal function onBtnMinimizeClick(arg1:flash.events.MouseEvent):void
        {
            this.main.dispatchEvent(new flash.events.Event("ChatEvent.MINIMIZED"));
            this.visible = false;
            if (this.resizerVisible) 
            {
                this.resize_arrow.visible = false;
                flash.ui.Mouse.show();
            }
            if (this.btnRestoreVisible) 
            {
                this.main.addChild(this.btnRestore);
            }
            return;
        }

        internal function onBtnRestoreClick(arg1:flash.events.MouseEvent):void
        {
            this.visible = true;
            if (this.main.contains(this.btnRestore)) 
            {
                this.main.removeChild(this.btnRestore);
            }
            return;
        }

        internal function onBtnLeftClick(arg1:flash.events.MouseEvent):void
        {
            this.tabBar.scrollToLeft();
            this.checkScrollButtons();
            return;
        }

        internal function onMouseOutInput(arg1:flash.events.MouseEvent):void
        {
            this.mouseOverInput = false;
            return;
        }

        internal function checkScrollButtons():void
        {
            this.btnRight.visible = this.tabBar.showRightButton();
            this.btnLeft.visible = this.tabBar.showLeftButton();
            return;
        }

        internal function onMouseOverInput(arg1:flash.events.MouseEvent):void
        {
            this.mouseOverInput = true;
            return;
        }

        internal function linkEvent(arg1:flash.events.TextEvent):void
        {
            var loc1:*=arg1.text.split("|");
            if (loc1[0] != "USER") 
            {
                if (loc1[0] == "INVITE") 
                {
                    this.main.joinInvitedRoom(parseInt(loc1[1]));
                }
            }
            else 
            {
                this.textInput.text = "/w " + loc1[1] + " ";
            }
            return;
        }

        internal function onMouseOverTextOutput(arg1:flash.events.MouseEvent):void
        {
            this.dragAllowed = false;
            return;
        }

        internal function onMouseOutTextOutput(arg1:flash.events.MouseEvent):void
        {
            this.dragAllowed = true;
            return;
        }

        internal function onMouseDownResizer(arg1:flash.events.MouseEvent):void
        {
            if (!this.resizable) 
            {
                return;
            }
            this.resizer.startDrag(false, this.hookRectangle);
            this.resizing = true;
            return;
        }

        internal function onMouseOverResizer(arg1:flash.events.MouseEvent):void
        {
            flash.ui.Mouse.hide();
            this.resize_arrow.visible = true;
            this.resize_arrow.x = this.main.mouseX;
            this.resize_arrow.y = this.main.mouseY;
            return;
        }

        internal function onMouseOutResizer(arg1:flash.events.MouseEvent):void
        {
            if (this.resizerVisible) 
            {
                if (!this.resizing) 
                {
                    this.resize_arrow.x = this.main.mouseX;
                    this.resize_arrow.y = this.main.mouseY;
                    this.resize_arrow.visible = false;
                    flash.ui.Mouse.show();
                }
            }
            return;
        }

        internal function onMouseMove(arg1:flash.events.MouseEvent):void
        {
            if (this.resizerVisible) 
            {
                this.resize_arrow.x = this.main.mouseX;
                this.resize_arrow.y = this.main.mouseY;
            }
            return;
        }

        public function repaint():void
        {
            var loc1:*=0;
            var loc2:*=0;
            if (this.resizerVisible) 
            {
                loc1 = this.resizer.x - this.superBitmap.getInitialHookPosition().x;
                loc2 = this.resizer.y - this.superBitmap.getInitialHookPosition().y;
            }
            if (this.resizerVisible) 
            {
                this.resize_arrow.x = this.main.mouseX;
                this.resize_arrow.y = this.main.mouseY;
            }
            this.superBitmap.setTotalWidth(this.superBitmap.getInitialWidth() + loc1);
            this.myScrollBar._move(this.superBitmap.getTotalWidth() - 300, this.superBitmap.getTotalHeight() - 150);
            this.textOutput.width = this.textOutputInitWidth + loc1;
            this.textInput.width = this.textInputInitWidth + loc1;
            this.tabBar.setMaskWidth(this.tabMaskInitWidth + loc1);
            this.btnMaximize.x = this.initialMaximizeXPos + loc1;
            this.superBitmap.setTotalHeight(this.superBitmap.getInitialHeight() + loc2);
            this.myScrollBar._move(this.superBitmap.getTotalWidth() - 300, this.superBitmap.getTotalHeight() - 150);
            this.textOutput.height = this.textOutputInitHeight + loc2;
            this.textInput.y = this.textInputInitY + loc2;
            this.repaintMask(this.superBitmap.getTotalWidth(), this.superBitmap.getTotalHeight());
            this.checkScrollButtons();
            return;
        }

        public function setSize(arg1:int, arg2:int):void
        {
            var loc1:*=0;
            var loc2:*=0;
            loc1 = arg1 - parseInt(String(this.settingsHandler.getVariable("minWidth")));
            loc2 = arg2 - parseInt(String(this.settingsHandler.getVariable("minHeight")));
            this.superBitmap.setTotalWidth(this.superBitmap.getInitialWidth() + loc1);
            this.myScrollBar._move(this.superBitmap.getTotalWidth() - 300, this.superBitmap.getTotalHeight() - 150);
            this.textOutput.width = this.textOutputInitWidth + loc1;
            this.textInput.width = this.textInputInitWidth + loc1;
            this.tabBar.setMaskWidth(this.tabMaskInitWidth + loc1);
            this.btnMaximize.x = this.initialMaximizeXPos + loc1;
            this.superBitmap.setTotalHeight(this.superBitmap.getInitialHeight() + loc2);
            this.myScrollBar._move(this.superBitmap.getTotalWidth() - 300, this.superBitmap.getTotalHeight() - 150);
            this.textOutput.height = this.textOutputInitHeight + loc2;
            this.textInput.y = this.textInputInitY + loc2;
            this.repaintMask(this.superBitmap.getTotalWidth(), this.superBitmap.getTotalHeight());
            this.checkScrollButtons();
            return;
        }

        internal function onMouseUp(arg1:flash.events.MouseEvent):void
        {
            var loc2:*=null;
            this.myScrollBar.stopScrolling();
            this.stopDrag();
            if (this.resizable) 
            {
                if (this.resizing) 
                {
                    this.resizer.stopDrag();
                    this.resizing = false;
                    loc2 = this.superBitmap.getResizerPosition();
                    this.resizer.x = loc2.x;
                    this.resizer.y = loc2.y;
                    this.resize_arrow.x = this.main.mouseX;
                    this.resize_arrow.y = this.main.mouseY;
                    this.resize_arrow.visible = false;
                    flash.ui.Mouse.show();
                    this.main.dispatchEvent(new flash.events.Event("ChatEvent.NEW_SIZE"));
                }
            }
            var loc1:*=this.getBounds(this.stage);
            if (this.newPositionFlag) 
            {
                if (!(this.oldChatPosX == loc1.x) || !(this.oldChatPosY == loc1.y)) 
                {
                    this.main.dispatchEvent(new flash.events.Event("ChatEvent.NEW_POSITION"));
                    if (this.main.getSaveLastPosition()) 
                    {
                        this.main.getSharedObject().data.xPosition = this.x;
                        this.main.getSharedObject().data.yPosition = this.y;
                        this.main.getSharedObject().flush();
                    }
                }
            }
            this.oldChatPosX = loc1.x;
            this.oldChatPosY = loc1.y;
            return;
        }

        internal function onMouseDown(arg1:flash.events.MouseEvent):void
        {
            if (!this.draggable) 
            {
                return;
            }
            if (this.mouseOverInput) 
            {
                return;
            }
            if (this.myScrollBar.isMouseOverScrollbar()) 
            {
                return;
            }
            if (this.resizing) 
            {
                return;
            }
            if (!this.dragAllowed) 
            {
                return;
            }
            this.startDrag(false, this.main.getViewportBounds());
            this.newPositionFlag = true;
            return;
        }

        internal function toggleView(arg1:flash.events.MouseEvent):void
        {
            this.flag1 = true;
            this.flag2 = true;
            if (this.viewMode != VIEW_NORMAL) 
            {
                if (this.viewMode == VIEW_MAXIMIZED) 
                {
                    this.autoMinimizeX = true;
                    this.autoMinimizeY = true;
                }
            }
            else 
            {
                this.autoMaximizeX = true;
                this.autoMaximizeY = true;
            }
            return;
        }

        internal function onKeyDown(arg1:flash.events.KeyboardEvent):void
        {
            var loc1:*=null;
            if (arg1.charCode != flash.ui.Keyboard.ENTER) 
            {
                if (arg1.keyCode != flash.ui.Keyboard.UP) 
                {
                    if (arg1.keyCode == flash.ui.Keyboard.DOWN) 
                    {
                        loc1 = this.getHistoryElement(HISTORY_DOWN);
                        if (loc1 != null) 
                        {
                            this.textInput.text = loc1;
                        }
                    }
                }
                else 
                {
                    loc1 = this.getHistoryElement(HISTORY_UP);
                    if (loc1 != null) 
                    {
                        this.textInput.text = loc1;
                    }
                }
            }
            else 
            {
                this.sendChatMessage(this.textInput.text);
            }
            return;
        }

        public function getHistoryElement(arg1:int):String
        {
            var loc1:*=arg1;
            switch (loc1) 
            {
                case HISTORY_UP:
                {
                    if (this.historyPointer > 0) 
                    {
                        var loc2:*=((loc1 = this).historyPointer - 1);
                        loc1.historyPointer = loc2;
                        return this.history[this.historyPointer];
                    }
                    break;
                }
                case HISTORY_DOWN:
                {
                    if (this.historyPointer < (this.history.length - 1)) 
                    {
                        loc2 = ((loc1 = this).historyPointer + 1);
                        loc1.historyPointer = loc2;
                        return this.history[this.historyPointer];
                    }
                    break;
                }
            }
            return null;
        }

        internal function isNewMessage(arg1:String):Boolean
        {
            if (arg1 != this.lastMessage) 
            {
                this.trys = 0;
            }
            else 
            {
                if (this.trys == 1) 
                {
                    this.output(net.bigpoint.as3chat.Message.TYPE_SYSTEM, this.languageHandler.getWord("globalchat.chat.system"), null, this.languageHandler.getWord("globalchat.chat.spamWarning"), this.main.getActiveRoomId());
                    return false;
                }
                var loc1:*;
                var loc2:*=((loc1 = this).trys + 1);
                loc1.trys = loc2;
            }
            if (this.lastMessageLength == 1 && arg1.length == 1) 
            {
                this.output(net.bigpoint.as3chat.Message.TYPE_SYSTEM, this.languageHandler.getWord("globalchat.chat.system"), null, this.languageHandler.getWord("globalchat.chat.spamWarning"), this.main.getActiveRoomId());
                return false;
            }
            return true;
        }

        public function sendChatMessage(arg1:String):void
        {
            var loc2:*=null;
            var loc3:*=null;
            var loc4:*=null;
            var loc5:*=null;
            var loc6:*=0;
            var loc7:*=null;
            if (this.main.isFastRegMode()) 
            {
                this.output(net.bigpoint.as3chat.Message.TYPE_SYSTEM, this.languageHandler.getWord("globalchat.chat.system"), null, this.languageHandler.getWord("globalchat.chat.fastReg"), this.main.getActiveRoomId());
                return;
            }
            arg1 = mx.utils.StringUtil.trim(arg1);
            if (arg1.length < 1) 
            {
                return;
            }
            if (!this.isNewMessage(arg1)) 
            {
                return;
            }
            if (this.spamCounter > 6) 
            {
                this.output(net.bigpoint.as3chat.Message.TYPE_SYSTEM, this.languageHandler.getWord("globalchat.chat.system"), null, this.languageHandler.getWord("globalchat.chat.fastTyping"), this.main.getActiveRoomId());
                return;
            }
            if (arg1.length > 255) 
            {
                this.output(net.bigpoint.as3chat.Message.TYPE_SYSTEM, this.languageHandler.getWord("globalchat.chat.system"), null, this.languageHandler.getWord("globalchat.chat.messageTooLong"), this.main.getActiveRoomId());
                return;
            }
            if (this.main.textListener != null) 
            {
                this.main.textListener(arg1);
            }
            var loc8:*;
            var loc9:*=((loc8 = this).spamCounter + 1);
            loc8.spamCounter = loc9;
            arg1 = arg1.replace(new RegExp("src=", "g"), "");
            arg1 = arg1.replace(new RegExp("href=", "g"), "");
            arg1 = arg1.replace(new RegExp("asfunction", "g"), "");
            arg1 = arg1.replace(new RegExp("</color>", "g"), "");
            arg1 = arg1.replace(new RegExp("MSG_SEPERATOR", "g"), "");
            arg1 = arg1.replace(new RegExp("PARAM_SEPERATOR", "g"), "");
            arg1 = arg1.replace(new RegExp("ATRIBUTE_SEPERATOR", "g"), "");
            arg1 = arg1.replace(new RegExp("OBJECT_SEPERATOR", "g"), "");
            arg1 = arg1.replace(new RegExp("LINE_SEPERATOR", "g"), "");
            if (arg1.split(" ")[0] == "/join") 
            {
                return;
            }
            if (arg1.split(" ")[0] == "/ignore") 
            {
                loc2 = arg1.split(" ")[1];
                this.main.ignoreUser(loc2);
                loc3 = (loc3 = this.languageHandler.getWord("globalchat.chat.userNowIgnored")).replace("%USER", loc2);
                this.output(net.bigpoint.as3chat.Message.TYPE_SYSTEM, this.languageHandler.getWord("globalchat.chat.system"), null, loc3, this.main.getActiveRoomId());
                this.addToHistory(arg1);
                this.textInput.text = "";
                return;
            }
            if (arg1.split(" ")[0] == "/allow") 
            {
                loc2 = arg1.split(" ")[1];
                this.main.allowUser(loc2);
                loc3 = (loc3 = this.languageHandler.getWord("globalchat.chat.userNoMoreIgnored")).replace("%USER", loc2);
                this.output(net.bigpoint.as3chat.Message.TYPE_SYSTEM, this.languageHandler.getWord("globalchat.chat.system"), null, loc3, this.main.getActiveRoomId());
                this.addToHistory(arg1);
                this.textInput.text = "";
                return;
            }
            if (arg1 == "/version") 
            {
                this.output(net.bigpoint.as3chat.Message.TYPE_SYSTEM, this.languageHandler.getWord("globalchat.chat.system"), null, "version:" + this.main.getChatVersion(), this.main.getActiveRoomId());
                this.addToHistory(arg1);
                this.textInput.text = "";
                return;
            }
            if (arg1 == "/help") 
            {
                this.printHelpMessage();
                this.addToHistory(arg1);
                this.textInput.text = "";
                return;
            }
            if (arg1.split(" ")[0] == "/createpr") 
            {
                if (this.main.scalableRooms) 
                {
                    if (arg1.split(" ").length > 2) 
                    {
                        loc4 = arg1.split(" ")[1];
                        loc5 = "";
                        loc6 = 2;
                        while (loc6 < arg1.split(" ").length) 
                        {
                            loc7 = arg1.split(" ")[loc6];
                            if (loc6 < (arg1.split(" ").length - 1)) 
                            {
                                loc7 = loc7 + net.bigpoint.as3chat.Constants.OBJECT_SEPERATOR;
                            }
                            loc5 = loc5 + loc7;
                            ++loc6;
                        }
                        this.main.createPrivateRoomWithUsers(loc4, loc5);
                        this.output(net.bigpoint.as3chat.Message.TYPE_SYSTEM, this.languageHandler.getWord("globalchat.chat.system"), null, "Raum erstellt und Spieler eingeladen", this.main.getActiveRoomId());
                    }
                    else 
                    {
                        this.output(net.bigpoint.as3chat.Message.TYPE_SYSTEM, this.languageHandler.getWord("globalchat.chat.system"), null, "Syntaxfehler: Überprüfen Sie Ihre Eingaben", this.main.getActiveRoomId());
                    }
                }
                this.addToHistory(arg1);
                this.textInput.text = "";
                return;
            }
            var loc1:*=[];
            loc1[0] = net.bigpoint.as3chat.Main.encodeString(arg1);
            this.main.sendCommand(net.bigpoint.as3chat.Constants.CMD_USER_MSG, loc1);
            this.lastMessage = arg1;
            this.lastMessageLength = arg1.length;
            this.addToHistory(arg1);
            this.textInput.text = "";
            return;
        }

        internal function printHelpMessage():void
        {
            this.output(net.bigpoint.as3chat.Message.TYPE_HELP, this.languageHandler.getWord("globalchat.chat.system"), null, "/create &#60;roomname&#62;@" + this.languageHandler.getWord("globalchat.help.create"), this.main.getActiveRoomId());
            this.output(net.bigpoint.as3chat.Message.TYPE_HELP, this.languageHandler.getWord("globalchat.chat.system"), null, "/createpr &#60;roomname&#62; &#60;user1&#62; &#60;user2&#62; ...@" + this.languageHandler.getWord("globalchat.help.createpr"), this.main.getActiveRoomId());
            this.output(net.bigpoint.as3chat.Message.TYPE_HELP, this.languageHandler.getWord("globalchat.chat.system"), null, "/close@" + this.languageHandler.getWord("globalchat.help.close"), this.main.getActiveRoomId());
            this.output(net.bigpoint.as3chat.Message.TYPE_HELP, this.languageHandler.getWord("globalchat.chat.system"), null, "/leave@" + this.languageHandler.getWord("globalchat.help.leave"), this.main.getActiveRoomId());
            this.output(net.bigpoint.as3chat.Message.TYPE_HELP, this.languageHandler.getWord("globalchat.chat.system"), null, "/invite &#60;user&#62;@" + this.languageHandler.getWord("globalchat.help.invite"), this.main.getActiveRoomId());
            this.output(net.bigpoint.as3chat.Message.TYPE_HELP, this.languageHandler.getWord("globalchat.chat.system"), null, "/w &#60;user&#62;@" + this.languageHandler.getWord("globalchat.help.whisper"), this.main.getActiveRoomId());
            this.output(net.bigpoint.as3chat.Message.TYPE_HELP, this.languageHandler.getWord("globalchat.chat.system"), null, "/ignore &#60;user&#62;@" + this.languageHandler.getWord("globalchat.help.ignore"), this.main.getActiveRoomId());
            this.output(net.bigpoint.as3chat.Message.TYPE_HELP, this.languageHandler.getWord("globalchat.chat.system"), null, "/allow &#60;user&#62;@" + this.languageHandler.getWord("globalchat.help.allow"), this.main.getActiveRoomId());
            this.output(net.bigpoint.as3chat.Message.TYPE_HELP, this.languageHandler.getWord("globalchat.chat.system"), null, "/users@" + this.languageHandler.getWord("globalchat.help.users"), this.main.getActiveRoomId());
            return;
        }

        internal function addToHistory(arg1:String):void
        {
            var loc1:*=0;
            while (loc1 < this.history.length) 
            {
                if (arg1 == this.history[loc1]) 
                {
                    return;
                }
                ++loc1;
            }
            if (this.history.length > 20) 
            {
                this.history.shift();
            }
            this.history.push(arg1);
            this.historyPointer = this.history.length;
            return;
        }

        public function output(arg1:int, arg2:String, arg3:String, arg4:String, arg5:int, arg6:int=-1):net.bigpoint.as3chat.Message
        {
            var loc1:*;
            if ((loc1 = this.main.getRoom(arg5)) == null) 
            {
                return null;
            }
            var loc2:*;
            (loc2 = new net.bigpoint.as3chat.Message(arg1, arg2, arg3, arg4)).setAdminLevelId(arg6);
            loc1.addMessage(loc2);
            if (arg5 == this.main.getActiveRoomId()) 
            {
                this.textOutput.htmlText = loc1.getAllMessages();
            }
            if (this.main.contains(this) && this.autoscroll) 
            {
                this.textOutput.scrollV = this.textOutput.maxScrollV;
                this.myScrollBar.setHolderToBottom();
            }
            if (!this.contains(this.myScrollBar) && this.textOutput.maxScrollV > 1) 
            {
                this.addChildAt(this.myScrollBar, 4);
            }
            return loc2;
        }

        public function selectTabIndexByRoomId(arg1:int):void
        {
            var loc2:*=null;
            var loc3:*=null;
            var loc1:*=this.main.getRoom(arg1);
            if (loc1.getRoomType() != net.bigpoint.as3chat.Room.SCALABLE_ROOM_PARENT) 
            {
                if (!this.isJoined(arg1)) 
                {
                    loc2 = this.languageHandler.getWord("globalchat.chat.joinRoom");
                    loc2 = loc2.replace("%ROOM", loc1.getRoomName());
                    loc3 = new net.bigpoint.as3chat.Message(net.bigpoint.as3chat.Message.TYPE_SYSTEM, this.languageHandler.getWord("globalchat.chat.system"), null, loc2);
                    loc1.addMessage(loc3);
                    this.addToJoined(arg1);
                }
            }
            this.tabBar.selectTab(arg1);
            this.main.stage.focus = this.textInput;
            this.main.setActiveRoomId(arg1);
            this.textOutput.htmlText = loc1.getAllMessages();
            this.textOutput.scrollV = this.textOutput.maxScrollV;
            return;
        }

        public function onTabChange(arg1:int):void
        {
            var loc1:*=null;
            var loc2:*=0;
            var loc3:*=null;
            if (this.flag) 
            {
                loc1 = new Array();
                loc1.push(arg1);
                if (this.main.scalableRooms) 
                {
                    loc2 = 0;
                    while (loc2 < this.main.getRoomList().length) 
                    {
                        if ((loc3 = this.main.getRoomList()[loc2]).getRoomId() == arg1) 
                        {
                            if (loc3.getRoomType() == net.bigpoint.as3chat.Room.SCALABLE_ROOM_PARENT) 
                            {
                                this.main.setActiveScalRoomId(arg1);
                                break;
                            }
                        }
                        ++loc2;
                    }
                }
                else 
                {
                    loc2 = 0;
                    while (loc2 < this.main.getRoomList().length) 
                    {
                        if ((loc3 = this.main.getRoomList()[loc2]).getRoomId() == arg1) 
                        {
                            if (loc3.getRoomType() == net.bigpoint.as3chat.Room.NORMAL_ROOM) 
                            {
                                this.main.setActiveNormalRoomId(arg1);
                            }
                        }
                        ++loc2;
                    }
                }
                this.main.sendCommand(net.bigpoint.as3chat.Constants.CMD_USER_JOIN, loc1);
                this.selectTabIndexByRoomId(arg1);
            }
            return;
        }

        public function addToJoined(arg1:int):void
        {
            this.joinedRoomIds.push(arg1);
            return;
        }

        internal function isJoined(arg1:int):Boolean
        {
            var loc1:*=0;
            while (loc1 < this.joinedRoomIds.length) 
            {
                if (arg1 == this.joinedRoomIds[loc1]) 
                {
                    return true;
                }
                ++loc1;
            }
            return false;
        }

        public function resetChat():void
        {
            this.tabBar.clearAll();
            this.joinedRoomIds.length = 0;
            this.history.length = 0;
            return;
        }

        public function updateTabBar():void
        {
            var loc4:*=null;
            var loc5:*=0;
            var loc6:*=null;
            var loc7:*=0;
            var loc8:*=0;
            var loc9:*=null;
            var loc10:*=null;
            var loc11:*=null;
            var loc12:*=null;
            var loc1:*=this.main.getRoomList();
            loc1.sortOn("tabOrder", Array.NUMERIC);
            var loc2:*=this.tabBar.getSelectedIndex();
            this.tabBar.clearAll();
            var loc3:*=0;
            while (loc3 < loc1.length) 
            {
                if ((loc4 = loc1[loc3]) != null) 
                {
                    loc5 = loc4.getRoomType();
                    loc6 = loc4.getRoomName();
                    loc7 = loc4.getCreatorId();
                    if ((loc8 = loc4.getCompanyId()) == -1) 
                    {
                        if (loc5 == net.bigpoint.as3chat.Room.NORMAL_ROOM || loc5 == net.bigpoint.as3chat.Room.SUPPORT_ROOM || loc5 == net.bigpoint.as3chat.Room.CLAN_ROOM || loc5 == net.bigpoint.as3chat.Room.GROUP_ROOM) 
                        {
                            this.tabBar.addItem(loc4.getRoomId(), loc6, loc4.getRoomName(), loc4.getRoomType());
                        }
                        else if (loc5 != net.bigpoint.as3chat.Room.SCALABLE_ROOM_PARENT) 
                        {
                            if (loc5 != net.bigpoint.as3chat.Room.DYNAMIC_ROOM) 
                            {
                                if (loc5 != net.bigpoint.as3chat.Room.SECTOR_ROOM) 
                                {
                                    if (loc5 == net.bigpoint.as3chat.Room.PRIVATE_ROOM && (this.main.getUserId() == loc7 || loc4.isInvited())) 
                                    {
                                        this.tabBar.addItem(loc4.getRoomId(), loc6, loc4.getRoomName(), loc4.getRoomType());
                                    }
                                }
                                else 
                                {
                                    this.tabBar.addItem(loc4.getRoomId(), loc6, loc4.getRoomName(), loc4.getRoomType());
                                }
                            }
                            else 
                            {
                                this.tabBar.addItem(loc4.getRoomId(), loc6, loc4.getRoomName(), loc4.getRoomType());
                            }
                        }
                        else 
                        {
                            loc9 = net.bigpoint.as3chat.ScalableRoom(loc4);
                            this.tabBar.addItem(loc9.getRoomId(), loc9.getRoomName(), loc9.getChildRoomName(), loc9.getRoomType());
                        }
                        this.tabBar.updateTab();
                        if (this.main.firstRun) 
                        {
                            (loc10 = new Array()).push(loc4.getRoomId());
                            this.main.sendCommand(net.bigpoint.as3chat.Constants.CMD_USER_JOIN, loc10);
                            this.main.setActiveRoomId(loc4.getRoomId());
                            this.main.setActiveNormalRoomId(loc4.getRoomId());
                            if (loc5 == net.bigpoint.as3chat.Room.DYNAMIC_ROOM || loc5 == net.bigpoint.as3chat.Room.SCALABLE_ROOM_PARENT) 
                            {
                                this.main.firstRoomId = loc4.getRoomId();
                                this.selectTabIndexByRoomId(this.main.firstRoomId);
                            }
                            else 
                            {
                                if (!this.isJoined(loc4.getRoomId())) 
                                {
                                    loc11 = (loc11 = this.languageHandler.getWord("globalchat.chat.joinRoom")).replace("%ROOM", loc4.getRoomName());
                                    loc12 = new net.bigpoint.as3chat.Message(net.bigpoint.as3chat.Message.TYPE_SYSTEM, this.languageHandler.getWord("globalchat.chat.system"), null, loc11);
                                    loc4.addMessage(loc12);
                                    this.addToJoined(loc4.getRoomId());
                                }
                                this.tabBar.selectTab(loc4.getRoomId());
                                this.textOutput.htmlText = loc4.getAllMessages();
                                this.textOutput.scrollV = this.textOutput.maxScrollV;
                            }
                            this.main.firstRun = false;
                        }
                    }
                    else if (this.main.isInCompanyIdList(loc8)) 
                    {
                        if (loc5 == net.bigpoint.as3chat.Room.NORMAL_ROOM || loc5 == net.bigpoint.as3chat.Room.SUPPORT_ROOM || loc5 == net.bigpoint.as3chat.Room.CLAN_ROOM || loc5 == net.bigpoint.as3chat.Room.GROUP_ROOM) 
                        {
                            this.tabBar.addItem(loc4.getRoomId(), loc6, loc4.getRoomName(), loc4.getRoomType());
                        }
                        else if (loc5 != net.bigpoint.as3chat.Room.SCALABLE_ROOM_PARENT) 
                        {
                            if (loc5 != net.bigpoint.as3chat.Room.DYNAMIC_ROOM) 
                            {
                                if (loc5 != net.bigpoint.as3chat.Room.SECTOR_ROOM) 
                                {
                                    if (loc5 == net.bigpoint.as3chat.Room.PRIVATE_ROOM && (this.main.getUserId() == loc7 || loc4.isInvited())) 
                                    {
                                        this.tabBar.addItem(loc4.getRoomId(), loc6, loc4.getRoomName(), loc4.getRoomType());
                                    }
                                }
                                else 
                                {
                                    this.tabBar.addItem(loc4.getRoomId(), loc6, loc4.getRoomName(), loc4.getRoomType());
                                }
                            }
                            else 
                            {
                                this.tabBar.addItem(loc4.getRoomId(), loc6, loc4.getRoomName(), loc4.getRoomType());
                            }
                        }
                        else 
                        {
                            loc9 = net.bigpoint.as3chat.ScalableRoom(loc4);
                            this.tabBar.addItem(loc9.getRoomId(), loc9.getRoomName(), loc9.getChildRoomName(), loc9.getRoomType());
                        }
                        this.tabBar.updateTab();
                        if (this.main.firstRun) 
                        {
                            (loc10 = new Array()).push(loc4.getRoomId());
                            this.main.sendCommand(net.bigpoint.as3chat.Constants.CMD_USER_JOIN, loc10);
                            this.main.setActiveRoomId(loc4.getRoomId());
                            this.main.setActiveNormalRoomId(loc4.getRoomId());
                            if (loc5 == net.bigpoint.as3chat.Room.DYNAMIC_ROOM || loc5 == net.bigpoint.as3chat.Room.SCALABLE_ROOM_PARENT) 
                            {
                                this.main.firstRoomId = loc4.getRoomId();
                                this.selectTabIndexByRoomId(this.main.firstRoomId);
                            }
                            else 
                            {
                                if (!this.isJoined(loc4.getRoomId())) 
                                {
                                    loc11 = (loc11 = this.languageHandler.getWord("globalchat.chat.joinRoom")).replace("%ROOM", loc4.getRoomName());
                                    loc12 = new net.bigpoint.as3chat.Message(net.bigpoint.as3chat.Message.TYPE_SYSTEM, this.languageHandler.getWord("globalchat.chat.system"), null, loc11);
                                    loc4.addMessage(loc12);
                                    this.addToJoined(loc4.getRoomId());
                                }
                                this.tabBar.selectTab(loc4.getRoomId());
                                this.textOutput.htmlText = loc4.getAllMessages();
                                this.textOutput.scrollV = this.textOutput.maxScrollV;
                            }
                            this.main.firstRun = false;
                        }
                    }
                }
                ++loc3;
            }
            if (this.main.scalableRooms) 
            {
                if (loc2 == -1) 
                {
                    this.tabBar.selectTabByIndex(loc2);
                }
                else 
                {
                    this.tabBar.selectTab(this.main.getActiveRoomId());
                }
            }
            else if (loc2 != -1) 
            {
                this.tabBar.selectTab(this.main.getActiveRoomId());
            }
            this.updateButtonsPositions();
            this.checkScrollButtons();
            return;
        }

        public function blockControls():void
        {
            this.btnMaximize.enabled = false;
            this.textInput.enabled = false;
            this.blocked = true;
            return;
        }

        public function parseBoolean(arg1:String):Boolean
        {
            if (arg1 == "true") 
            {
                return true;
            }
            return false;
        }

        public function getTabBar():net.bigpoint.as3chat.MyTabBar
        {
            return this.tabBar;
        }

        public function getTextInput():fl.controls.TextInput
        {
            return this.textInput;
        }

        public function getTextOutput():flash.text.TextField
        {
            return this.textOutput;
        }

        public function getScrollBarShapeColor():String
        {
            return this.scrollBarShapeColor;
        }

        public function isDraggable():Boolean
        {
            return this.draggable;
        }

        public function setDraggable(arg1:Boolean):void
        {
            this.draggable = arg1;
            return;
        }

        public function getScrollBarAlpha():int
        {
            return this.scrollBarAlpha;
        }

        public function isUseScrollBarBg():Boolean
        {
            return this.useScrollBarBg;
        }

        public function setUseScrollBarBg(arg1:Boolean):void
        {
            this.useScrollBarBg = arg1;
            return;
        }

        internal function onTimerTick(arg1:flash.events.TimerEvent):void
        {
            if (this.myScrollBar == null) 
            {
                return;
            }
            var loc1:*=this.myScrollBar.getScrollDirection();
            switch (loc1) 
            {
                case net.bigpoint.as3chat.ScrollBar.SCROLL_JUMP_UP:
                {
                    this.textOutput.scrollV = 1;
                    break;
                }
                case net.bigpoint.as3chat.ScrollBar.SCROLL_UP_FAST:
                {
                    if (this.textOutput.scrollV > 0) 
                    {
                        this.textOutput.scrollV = this.textOutput.scrollV - 4;
                    }
                    break;
                }
                case net.bigpoint.as3chat.ScrollBar.SCROLL_UP:
                {
                    if (this.textOutput.scrollV > 0) 
                    {
                        var loc2:*=((loc1 = this.textOutput).scrollV - 1);
                        loc1.scrollV = loc2;
                    }
                    break;
                }
                case net.bigpoint.as3chat.ScrollBar.SCROLL_DOWN:
                {
                    if (this.textOutput.scrollV < this.textOutput.maxScrollV) 
                    {
                        loc2 = ((loc1 = this.textOutput).scrollV + 1);
                        loc1.scrollV = loc2;
                    }
                    break;
                }
                case net.bigpoint.as3chat.ScrollBar.SCROLL_DOWN_FAST:
                {
                    if (this.textOutput.scrollV < this.textOutput.maxScrollV) 
                    {
                        this.textOutput.scrollV = this.textOutput.scrollV + 4;
                    }
                    break;
                }
                case net.bigpoint.as3chat.ScrollBar.SCROLL_JUMP_DOWN:
                {
                    this.textOutput.scrollV = this.textOutput.maxScrollV;
                    break;
                }
            }
            return;
        }

        public function getBtnRestore():flash.display.MovieClip
        {
            return this.btnRestore;
        }

        public function cleanup():void
        {
            this.removeAutoscrollTimer();
            this.removeEventListener(flash.events.Event.ENTER_FRAME, this._onEnterFrame);
            this.removeEventListener(flash.events.MouseEvent.MOUSE_MOVE, this.onMouseMove);
            this.removeEventListener(flash.events.MouseEvent.MOUSE_OUT, this.onMouseOutResizer);
            if (this.scrollTimer != null) 
            {
                this.scrollTimer.stop();
                this.scrollTimer.removeEventListener(flash.events.TimerEvent.TIMER, this.onTimerTick);
            }
            if (this.spamTimer != null) 
            {
                this.spamTimer.stop();
                this.spamTimer.removeEventListener(flash.events.TimerEvent.TIMER, this.reduceSpamCounter);
            }
            this.removeEventListener(flash.events.MouseEvent.MOUSE_DOWN, this.onMouseDown);
            if (this.main.stage != null) 
            {
                this.main.stage.removeEventListener(flash.events.MouseEvent.MOUSE_UP, this.onMouseUp);
            }
            this.removeEventListener(flash.events.MouseEvent.MOUSE_UP, this.onMouseUp);
            this.textOutput.removeEventListener(flash.events.TextEvent.LINK, this.linkEvent);
            this.textOutput.removeEventListener(flash.events.MouseEvent.MOUSE_OVER, this.onMouseOverTextOutput);
            this.textOutput.removeEventListener(flash.events.MouseEvent.MOUSE_OUT, this.onMouseOutTextOutput);
            this.textInput.removeEventListener(flash.events.MouseEvent.MOUSE_OVER, this.onMouseOverInput);
            this.textInput.removeEventListener(flash.events.MouseEvent.MOUSE_OUT, this.onMouseOutInput);
            this.btnRight.removeEventListener(flash.events.MouseEvent.CLICK, this.onBtnRightClick);
            this.btnRight.removeEventListener(flash.events.MouseEvent.MOUSE_OVER, this.onBtnRightOver);
            this.btnRight.removeEventListener(flash.events.MouseEvent.MOUSE_OUT, this.onBtnRightOut);
            this.btnRight.buttonMode = true;
            this.btnLeft = new flash.display.MovieClip();
            this.btnLeft.addChild(net.bigpoint.flashcorelib.resources.MediaHandler.getInstance().getBitmap("btn_left_normal"));
            this.btnLeft.removeEventListener(flash.events.MouseEvent.CLICK, this.onBtnLeftClick);
            this.btnLeft.removeEventListener(flash.events.MouseEvent.MOUSE_OVER, this.onBtnLeftOver);
            this.btnLeft.removeEventListener(flash.events.MouseEvent.MOUSE_OUT, this.onBtnLeftOut);
            this.btnLeft.buttonMode = true;
            if (this.btnMinimizeVisible) 
            {
                this.btnMinimize.removeEventListener(flash.events.MouseEvent.CLICK, this.onBtnMinimizeClick);
                this.btnMinimize.removeEventListener(flash.events.MouseEvent.MOUSE_OVER, this.onBtnMinimizeOver);
                this.btnMinimize.removeEventListener(flash.events.MouseEvent.MOUSE_OUT, this.onBtnMinimizeOut);
                this.btnRestore.removeEventListener(flash.events.MouseEvent.CLICK, this.onBtnRestoreClick);
                this.btnRestore.removeEventListener(flash.events.MouseEvent.MOUSE_OVER, this.onBtnRestoreOver);
                this.btnRestore.removeEventListener(flash.events.MouseEvent.MOUSE_OUT, this.onBtnRestoreOut);
            }
            this.btnMaximize.removeEventListener(flash.events.MouseEvent.CLICK, this.toggleView);
            this.removeEventListener(flash.events.KeyboardEvent.KEY_DOWN, this.onKeyDown);
            if (this.resizerVisible) 
            {
                this.resizer.removeEventListener(flash.events.MouseEvent.MOUSE_DOWN, this.onMouseDownResizer);
                this.resizer.removeEventListener(flash.events.MouseEvent.MOUSE_OVER, this.onMouseOverResizer);
                this.resizer.removeEventListener(flash.events.MouseEvent.MOUSE_OUT, this.onMouseOutResizer);
            }
            this.tabBar.clearAll();
            this.myScrollBar.cleanup();
            return;
        }

        public function isResizable():Boolean
        {
            return this.resizable;
        }

        public function isResizerVisible():Boolean
        {
            return this.resizerVisible;
        }

        public function getMain():net.bigpoint.as3chat.Main
        {
            return this.main;
        }

        public function getResizer():flash.display.Sprite
        {
            return this.resizer;
        }

        public function isBtnRestoreVisible():Boolean
        {
            return this.btnRestoreVisible;
        }

        public function get autoscroll():Boolean
        {
            return this._autoscroll;
        }

        public function set autoscroll(arg1:Boolean):void
        {
            this._autoscroll = arg1;
            if (arg1) 
            {
                this.removeAutoscrollTimer();
            }
            return;
        }

        public function startAutoscrollTimer():void
        {
            this.removeAutoscrollTimer();
            this.autoscrollTimer = new flash.utils.Timer(1000, 0);
            this.autoscrollTimer.addEventListener(flash.events.TimerEvent.TIMER, this.handleAutoscrollTimer);
            this.autoscrollTimer.start();
            return;
        }

        internal function removeAutoscrollTimer():void
        {
            if (this.autoscrollTimer != null) 
            {
                this.autoscrollTimer.stop();
                this.autoscrollTimer.removeEventListener(flash.events.TimerEvent.TIMER, this.handleAutoscrollTimer);
                this.autoscrollCnt = this.autoscrollInitialCnt;
            }
            return;
        }

        internal function handleAutoscrollTimer(arg1:flash.events.TimerEvent):void
        {
            var loc1:*;
            var loc2:*=((loc1 = this).autoscrollCnt - 1);
            loc1.autoscrollCnt = loc2;
            if (this.autoscrollCnt == -1) 
            {
                this.removeAutoscrollTimer();
                this.autoscroll = true;
            }
            return;
        }

        
        {
            MODE_DEFAULT = 0;
            MODE_ERROR = 1;
            HISTORY_UP = 0;
            HISTORY_DOWN = 1;
            VIEW_NORMAL = 0;
            VIEW_MAXIMIZED = 1;
        }

        public function isMinimizeButtonVisible():Boolean
        {
            return this.btnMinimizeVisible;
        }

        internal function setMask():void
        {
            this.chatMask = new flash.display.Sprite();
            this.chatMask.graphics.beginFill(16763904);
            this.chatMask.graphics.drawRect(0, 0, 300, 150);
            this.chatMask.mouseEnabled = false;
            this.addChild(this.chatMask);
            this.mask = this.chatMask;
            return;
        }

        internal function repaintMask(arg1:int, arg2:int):void
        {
            this.chatMask.width = arg1;
            this.chatMask.height = arg2;
            return;
        }

        internal var main:net.bigpoint.as3chat.Main;

        internal var tabBar:net.bigpoint.as3chat.MyTabBar;

        internal var textOutput:flash.text.TextField;

        internal var textOutputInitWidth:int;

        internal var textOutputInitHeight:int;

        internal var textInput:fl.controls.TextInput;

        internal var textInputInitWidth:int;

        internal var textInputInitY:int;

        internal var flag:Boolean=true;

        internal var history:Array;

        internal var historyPointer:int;

        internal var chatFontFace:String;

        internal var chatFontSize:int;

        internal var chatFontColor:String;

        internal var systemFontColor:String;

        internal var adminFontColor:String;

        internal var whisperFontColor:String;

        internal var scrollBarAlpha:int=0;

        internal var joinedRoomIds:Array;

        internal var languageHandler:net.bigpoint.flashcorelib.resources.LanguageHandler;

        internal var mediaHandler:net.bigpoint.flashcorelib.resources.MediaHandler;

        internal var settingsHandler:net.bigpoint.flashcorelib.resources.SettingsHandler;

        internal var lastMessage:String="";

        internal var resizer:flash.display.Sprite;

        internal var btnMaximize:flash.display.SimpleButton;

        internal var mode:int;

        internal var errorMsg:String;

        internal var blocked:Boolean;

        internal var _autoscroll:Boolean=true;

        internal var draggable:Boolean;

        internal var dragAllowed:Boolean=true;

        internal var tabMaskInitWidth:int;

        public var resizing:Boolean;

        internal var viewMode:int;

        internal var hookRectangle:flash.geom.Rectangle;

        internal var superBitmap:net.bigpoint.as3chat.SuperBitmap;

        internal var autoMaximizeX:Boolean=false;

        internal var autoMaximizeY:Boolean=false;

        internal var autoMinimizeX:Boolean=false;

        internal var autoMinimizeY:Boolean=false;

        internal var tabVisible:Boolean=true;

        internal var flag1:Boolean=false;

        internal var flag2:Boolean=false;

        internal var initialMaximizeXPos:int;

        internal var downRight:flash.display.Bitmap;

        internal var myScrollBar:net.bigpoint.as3chat.ScrollBar;

        internal var lastMessageLength:Number=0;

        internal var scrollTimer:flash.utils.Timer;

        internal var spamTimer:flash.utils.Timer;

        internal var trys:Number=0;

        internal var spamCounter:int=0;

        internal var chatMask:flash.display.Sprite;

        internal var mouseOverInput:Boolean;

        internal var btnRight:flash.display.MovieClip;

        internal var btnLeft:flash.display.MovieClip;

        internal var btnRestore:flash.display.MovieClip;

        internal var resizable:Boolean=true;

        internal var resizerVisible:Boolean=true;

        internal var autoscrollTimer:flash.utils.Timer;

        internal var autoscrollInitialCnt:int=5;

        internal var autoscrollCnt:int;

        internal var useScrollBarBg:Boolean;

        internal var btnMinimizeVisible:Boolean;

        internal var btnRestoreVisible:Boolean;

        internal var resize_arrow:flash.display.MovieClip;

        internal var oldChatPosX:int=-1;

        internal var oldChatPosY:int=-1;

        internal var newPositionFlag:Boolean;

        internal var btnMinimize:flash.display.MovieClip;

        public static var MODE_DEFAULT:int=0;

        public static var MODE_ERROR:int=1;

        public static var HISTORY_UP:int=0;

        public static var HISTORY_DOWN:int=1;

        public static var VIEW_NORMAL:int=0;

        public static var VIEW_MAXIMIZED:int=1;

        internal var scrollBarShapeColor:String;
    }
}
