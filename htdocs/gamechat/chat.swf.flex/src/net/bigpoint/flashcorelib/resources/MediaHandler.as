package net.bigpoint.flashcorelib.resources 
{
    import flash.display.*;
    import flash.events.*;
    import flash.media.*;
    import flash.net.*;
    
    public class MediaHandler extends flash.events.EventDispatcher
    {
        public function MediaHandler()
        {
            this.movieclips = new Array();
            this.bitmaps = new Array();
            this.sounds = new Array();
            super();
            if (!ALLOWINSTANTIATION) 
            {
                throw new Error("Error: Instantiation failed: Use getInstance() instead of new.");
            }
            this.queue = new Array();
            return;
        }

        public function getBitmap(arg1:String):flash.display.Bitmap
        {
            var loc1:*=this.bitmaps[arg1];
            if (loc1 == null) 
            {
                return null;
            }
            return loc1;
        }

        
        {
            TYPE_SOUND = 0;
            TYPE_BITMAP = 1;
            TYPE_MOVIECLIP = 2;
            instance = null;
            ALLOWINSTANTIATION = false;
        }

        public function getSound(arg1:String):flash.media.Sound
        {
            var loc1:*=this.sounds[arg1];
            if (loc1 == null) 
            {
                throw new Error("Cannot find Sound \"" + arg1 + "\" in MediaHandler!");
            }
            return loc1;
        }

        public function addToQueue(arg1:int, arg2:Array, arg3:Array, arg4:String=null):void
        {
            var loc1:*=new net.bigpoint.flashcorelib.resources.LoaderQueue(arg1, arg2, arg3, arg4);
            this.queue.push(loc1);
            return;
        }

        public function loadMedia():void
        {
            this._processQueue();
            return;
        }

        internal function loadBitmaps(arg1:Array, arg2:Array, arg3:String=null):void
        {
            this.bitmapKeys = arg1;
            this.bitmapFilenames = arg2;
            this.bitmapDirectory = arg3;
            this.loadNextBitmap();
            return;
        }

        internal function _processQueue():void
        {
            var loc1:*=null;
            if (this.queue[0] != null) 
            {
                loc1 = net.bigpoint.flashcorelib.resources.LoaderQueue(this.queue[0]);
                var loc2:*=loc1.getMediaType();
                switch (loc2) 
                {
                    case TYPE_SOUND:
                    {
                        this.loadSounds(loc1.getKeys(), loc1.getFilenames(), loc1.getDirectory());
                        break;
                    }
                    case TYPE_BITMAP:
                    {
                        this.loadBitmaps(loc1.getKeys(), loc1.getFilenames(), loc1.getDirectory());
                        break;
                    }
                    case TYPE_MOVIECLIP:
                    {
                        this.loadMovieclips(loc1.getKeys(), loc1.getFilenames(), loc1.getDirectory());
                        break;
                    }
                }
            }
            return;
        }

        internal function loadMovieclips(arg1:Array, arg2:Array, arg3:String=null):void
        {
            this.movieClipKeys = arg1;
            this.movieClipFilenames = arg2;
            this.movieclipDirectory = arg3;
            this.loadNextMovieclip();
            return;
        }

        internal function loadSounds(arg1:Array, arg2:Array, arg3:String=null):void
        {
            this.soundKeys = arg1;
            this.soundFilenames = arg2;
            this.soundDirectory = arg3;
            this.loadNextSound();
            return;
        }

        internal function loadNextSound():void
        {
            var sound:flash.media.Sound;
            var soundUrlRequest:flash.net.URLRequest;

            var loc1:*;
            soundUrlRequest = null;
            this.currSoundKey = this.soundKeys[this.soundCnt];
            this.currSoundFilename = this.soundFilenames[this.soundCnt];
            sound = new flash.media.Sound();
            sound.addEventListener(flash.events.Event.COMPLETE, this._onSoundComplete);
            sound.addEventListener(flash.events.IOErrorEvent.IO_ERROR, this.handleIOErrorSound);
            try 
            {
                if (this.soundDirectory != null) 
                {
                    soundUrlRequest.url = this.soundDirectory + "/" + this.currSoundFilename;
                }
                else 
                {
                    soundUrlRequest.url = this.currSoundFilename;
                }
                sound.load(soundUrlRequest);
            }
            catch (e:Error)
            {
            };
            return;
        }

        internal function _onSoundComplete(arg1:flash.events.Event):void
        {
            var loc2:*=null;
            var loc1:*=arg1.target as flash.media.Sound;
            this.sounds[this.currSoundKey] = loc1;
            loc1.removeEventListener(flash.events.Event.COMPLETE, this._onSoundComplete);
            loc1.removeEventListener(flash.events.IOErrorEvent.IO_ERROR, this.handleIOErrorSound);
            var loc3:*;
            var loc4:*=((loc3 = this).soundCnt + 1);
            loc3.soundCnt = loc4;
            if (this.progressBarMode) 
            {
                loc2 = new net.bigpoint.flashcorelib.resources.MediaHandlerEvent(net.bigpoint.flashcorelib.resources.MediaHandlerEvent.MEDIALOADED);
                loc2.setName(this.currSoundKey);
                loc2.setMediaType(TYPE_SOUND);
                loc2.setCnt(this.soundCnt);
                loc2.setMaxCnt(this.soundFilenames.length);
                dispatchEvent(loc2);
            }
            if (this.soundCnt != this.soundFilenames.length) 
            {
                this.loadNextSound();
            }
            else 
            {
                this.checkQueue();
            }
            return;
        }

        internal function loadNextBitmap():void
        {
            var bitmapUrlRequest:flash.net.URLRequest;
            var _bitmapLoader:flash.display.Loader;

            var loc1:*;
            bitmapUrlRequest = null;
            _bitmapLoader = null;
            this.currBitmapKey = this.bitmapKeys[this.bitmapCnt];
            this.currBitmapFilename = this.bitmapFilenames[this.bitmapCnt];
            try 
            {
                bitmapUrlRequest = new flash.net.URLRequest();
                if (this.bitmapDirectory != null) 
                {
                    bitmapUrlRequest.url = this.bitmapDirectory + "/" + this.currBitmapFilename;
                }
                else 
                {
                    bitmapUrlRequest.url = this.currBitmapFilename;
                }
                _bitmapLoader = new flash.display.Loader();
                _bitmapLoader.load(bitmapUrlRequest);
                _bitmapLoader.contentLoaderInfo.addEventListener(flash.events.Event.COMPLETE, this.onBitmapComplete);
                _bitmapLoader.contentLoaderInfo.addEventListener(flash.events.IOErrorEvent.IO_ERROR, this.handleIOErrorBitmap);
            }
            catch (e:Error)
            {
            };
            return;
        }

        internal function loadNextMovieclip():void
        {
            var movieclipUrlRequest:flash.net.URLRequest;
            var _movieclipLoader:flash.display.Loader;

            var loc1:*;
            movieclipUrlRequest = null;
            _movieclipLoader = null;
            this.currMovieclipKey = this.movieClipKeys[this.movieclipCnt];
            this.currMovieclipFilename = this.movieClipFilenames[this.movieclipCnt];
            try 
            {
                movieclipUrlRequest = new flash.net.URLRequest();
                if (this.movieclipDirectory != null) 
                {
                    movieclipUrlRequest.url = this.movieclipDirectory + "/" + this.currMovieclipFilename;
                }
                else 
                {
                    movieclipUrlRequest.url = this.currMovieclipFilename;
                }
                _movieclipLoader = new flash.display.Loader();
                _movieclipLoader.contentLoaderInfo.addEventListener(flash.events.Event.COMPLETE, this.onMovieclipComplete);
                _movieclipLoader.contentLoaderInfo.addEventListener(flash.events.IOErrorEvent.IO_ERROR, this.handleIOErrorMovieclip);
                _movieclipLoader.load(movieclipUrlRequest);
            }
            catch (e:Error)
            {
            };
            return;
        }

        internal function checkQueue():void
        {
            var loc1:*=null;
            if (this.queue[1] == null) 
            {
                loc1 = new net.bigpoint.flashcorelib.resources.MediaHandlerEvent(net.bigpoint.flashcorelib.resources.MediaHandlerEvent.ALLMEDIALOADED);
                dispatchEvent(loc1);
                this.resetAll();
            }
            else 
            {
                this.queue.splice(0, 1);
                this._processQueue();
            }
            return;
        }

        internal function onMovieclipComplete(arg1:flash.events.Event):void
        {
            var loc2:*=null;
            var loc1:*=arg1.target as flash.display.LoaderInfo;
            loc1.removeEventListener(flash.events.Event.COMPLETE, this.onMovieclipComplete);
            loc1.removeEventListener(flash.events.IOErrorEvent.IO_ERROR, this.handleIOErrorMovieclip);
            this.movieclips[this.currMovieclipKey] = flash.display.MovieClip(loc1.content);
            var loc3:*;
            var loc4:*=((loc3 = this).movieclipCnt + 1);
            loc3.movieclipCnt = loc4;
            if (this.progressBarMode) 
            {
                loc2 = new net.bigpoint.flashcorelib.resources.MediaHandlerEvent(net.bigpoint.flashcorelib.resources.MediaHandlerEvent.MEDIALOADED);
                loc2.setName(this.currMovieclipKey);
                loc2.setMediaType(TYPE_MOVIECLIP);
                loc2.setCnt(this.movieclipCnt);
                loc2.setMaxCnt(this.movieClipFilenames.length);
                dispatchEvent(loc2);
            }
            if (this.movieclipCnt != this.movieClipFilenames.length) 
            {
                this.loadNextMovieclip();
            }
            else 
            {
                this.checkQueue();
            }
            return;
        }

        internal function onBitmapComplete(arg1:flash.events.Event):void
        {
            var loc2:*=null;
            var loc1:*=arg1.target as flash.display.LoaderInfo;
            loc1.removeEventListener(flash.events.Event.COMPLETE, this.onBitmapComplete);
            loc1.removeEventListener(flash.events.IOErrorEvent.IO_ERROR, this.handleIOErrorBitmap);
            this.bitmaps[this.currBitmapKey] = flash.display.Bitmap(loc1.content);
            var loc3:*;
            var loc4:*=((loc3 = this).bitmapCnt + 1);
            loc3.bitmapCnt = loc4;
            if (this.progressBarMode) 
            {
                loc2 = new net.bigpoint.flashcorelib.resources.MediaHandlerEvent(net.bigpoint.flashcorelib.resources.MediaHandlerEvent.MEDIALOADED);
                loc2.setName(this.currBitmapKey);
                loc2.setMediaType(TYPE_BITMAP);
                loc2.setCnt(this.bitmapCnt);
                loc2.setMaxCnt(this.bitmapFilenames.length);
                dispatchEvent(loc2);
            }
            if (this.bitmapCnt != this.bitmapFilenames.length) 
            {
                this.loadNextBitmap();
            }
            else 
            {
                this.checkQueue();
            }
            return;
        }

        internal function handleIOErrorMovieclip(arg1:flash.events.IOErrorEvent):void
        {
            var loc1:*=new net.bigpoint.flashcorelib.resources.MediaHandlerEvent(net.bigpoint.flashcorelib.resources.MediaHandlerEvent.ERROR_LOADING_MEDIA);
            loc1.setErrorMsg("Could not load Media: " + this.currMovieclipFilename);
            loc1.setMediaType(TYPE_MOVIECLIP);
            dispatchEvent(loc1);
            return;
        }

        internal function handleIOErrorBitmap(arg1:flash.events.IOErrorEvent):void
        {
            var loc1:*=new net.bigpoint.flashcorelib.resources.MediaHandlerEvent(net.bigpoint.flashcorelib.resources.MediaHandlerEvent.ERROR_LOADING_MEDIA);
            loc1.setErrorMsg("Could not load Media: " + this.currBitmapFilename);
            loc1.setMediaType(TYPE_BITMAP);
            dispatchEvent(loc1);
            return;
        }

        internal function handleIOErrorSound(arg1:flash.events.IOErrorEvent):void
        {
            return;
        }

        public function getProgressBarMode():Boolean
        {
            return this.progressBarMode;
        }

        public function addBitmap(arg1:String, arg2:flash.display.Bitmap):void
        {
            this.bitmaps[arg1] = arg2;
            return;
        }

        public function setProgressBarMode(arg1:Boolean):void
        {
            this.progressBarMode = arg1;
            return;
        }

        public static function getInstance():net.bigpoint.flashcorelib.resources.MediaHandler
        {
            if (instance == null) 
            {
                ALLOWINSTANTIATION = true;
                instance = new MediaHandler();
                ALLOWINSTANTIATION = false;
            }
            return instance;
        }

        internal function resetAll():void
        {
            this.queue.length = 0;
            this.bitmapCnt = 0;
            this.movieclipCnt = 0;
            this.soundCnt = 0;
            return;
        }

        public function getMovieClip(arg1:String):flash.display.MovieClip
        {
            var loc1:*=this.movieclips[arg1];
            if (loc1 == null) 
            {
                throw new Error("Cannot find MovieClip \"" + arg1 + "\" in MediaHandler!");
            }
            return loc1;
        }

        internal var queue:Array;

        internal var progressBarMode:Boolean=true;

        internal var movieclips:Array;

        internal var bitmaps:Array;

        internal var sounds:Array;

        internal var movieclipCnt:int;

        internal var soundCnt:int;

        internal var movieClipFilenames:Array;

        internal var movieClipKeys:Array;

        internal var bitmapFilenames:Array;

        internal var bitmapKeys:Array;

        internal var soundFilenames:Array;

        internal var soundKeys:Array;

        internal var bitmapDirectory:String;

        internal var movieclipDirectory:String;

        internal var soundDirectory:String;

        internal var currBitmapKey:String;

        internal var currBitmapFilename:String;

        internal var currMovieclipKey:String;

        internal var currSoundKey:String;

        internal var currSoundFilename:String;

        internal var bitmapCnt:int;

        public static var TYPE_BITMAP:int=1;

        public static var TYPE_MOVIECLIP:int=2;

        internal static var instance:net.bigpoint.flashcorelib.resources.MediaHandler=null;

        internal static var ALLOWINSTANTIATION:Boolean=false;

        internal var currMovieclipFilename:String;

        public static var TYPE_SOUND:int=0;
    }
}
