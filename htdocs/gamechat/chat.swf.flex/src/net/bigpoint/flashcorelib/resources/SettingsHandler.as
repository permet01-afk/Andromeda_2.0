package net.bigpoint.flashcorelib.resources 
{
    import flash.events.*;
    import flash.net.*;
    import mx.logging.*;
    
    public class SettingsHandler extends flash.events.EventDispatcher
    {
        public function SettingsHandler()
        {
            this.variables = new Array();
            this.bitmapFilenames = new Array();
            this.bitmapKeys = new Array();
            this.bitmapCategorys = new Array();
            this.movieClipFilenames = new Array();
            this.movieClipKeys = new Array();
            this.soundFilenames = new Array();
            this.soundKeys = new Array();
            super();
            if (!ALLOWINSTANTIATION) 
            {
                throw new Error("Error: Instantiation failed: Use getInstance() instead of new.");
            }
            return;
        }

        public function replaceVariable(arg1:String, arg2:String):void
        {
            var loc1:*=null;
            var loc2:*=0;
            var loc3:*=this.variables;
            for (loc1 in loc3) 
            {
                if (loc1 != arg1) 
                {
                    continue;
                }
                this.variables.splice(loc1, 1);
                break;
            }
            this.variables[arg1] = arg2;
            loc2 = 0;
            loc3 = this.variables;
            for (loc1 in loc3) 
            {
                if (loc1 != arg1) 
                {
                    continue;
                }
            }
            return;
        }

        internal function addSoundFilename(arg1:String):void
        {
            this.soundFilenames.push(arg1);
            return;
        }

        internal function addSoundKey(arg1:String):void
        {
            this.soundKeys.push(arg1);
            return;
        }

        internal function addBitmapFilename(arg1:String):void
        {
            this.bitmapFilenames.push(arg1);
            return;
        }

        internal function addBitmapCategory(arg1:String):void
        {
            this.bitmapCategorys.push(arg1);
            return;
        }

        internal function loadSkinSettings():void
        {
            this.urlRequest = new flash.net.URLRequest(this.skinXMLPath);
            this.urlLoader = new flash.net.URLLoader();
            this.urlLoader.addEventListener(flash.events.Event.COMPLETE, this.skinSettingsLoaded);
            this.urlLoader.addEventListener(flash.events.IOErrorEvent.IO_ERROR, this.onErrorLoadSkinSettings);
            this.urlLoader.load(this.urlRequest);
            logger.debug("loadSkinSettings:" + this.urlRequest);
            return;
        }

        internal function addBitmapKey(arg1:String):void
        {
            this.bitmapKeys.push(arg1);
            return;
        }

        internal function addMovieClipFilename(arg1:String):void
        {
            this.movieClipFilenames.push(arg1);
            return;
        }

        internal function addMovieClipKey(arg1:String):void
        {
            this.movieClipKeys.push(arg1);
            return;
        }

        public function getBitmapFilenames():Array
        {
            return this.bitmapFilenames;
        }

        public function getBitmapKeys():Array
        {
            return this.bitmapKeys;
        }

        internal function getMovieClipFilenames():Array
        {
            return this.movieClipFilenames;
        }

        internal function getMovieClipKeys():Array
        {
            return this.movieClipKeys;
        }

        internal function getSoundFilenames():Array
        {
            return this.soundFilenames;
        }

        internal function getSoundKeys():Array
        {
            return this.soundKeys;
        }

        public function getBitmapCategorys():Array
        {
            return this.bitmapCategorys;
        }

        public function getSharedObject():flash.net.SharedObject
        {
            return this.sharedObject;
        }

        public function setBitmapsPath(arg1:String):void
        {
            this.bitmapsPath = arg1;
            return;
        }

        public static function getInstance():net.bigpoint.flashcorelib.resources.SettingsHandler
        {
            if (instance == null) 
            {
                ALLOWINSTANTIATION = true;
                instance = new SettingsHandler();
                ALLOWINSTANTIATION = false;
            }
            return instance;
        }

        
        {
            instance = null;
            ALLOWINSTANTIATION = false;
        }

        public function initSharedObject(arg1:String):void
        {
            this.sharedObject = flash.net.SharedObject.getLocal(arg1);
            return;
        }

        public function loadSettings(arg1:String):void
        {
            logger.debug("loadSettings:" + arg1);
            this.url = arg1;
            this.urlRequest = new flash.net.URLRequest(arg1);
            this.urlLoader = new flash.net.URLLoader();
            this.urlLoader.addEventListener(flash.events.Event.COMPLETE, this.settingsLoaded);
            this.urlLoader.addEventListener(flash.events.IOErrorEvent.IO_ERROR, this.onErrorLoadSettings);
            this.urlLoader.load(this.urlRequest);
            return;
        }

        internal function settingsLoaded(arg1:flash.events.Event):void
        {
            var loc1:*=null;
            var loc2:*=null;
            logger.debug("settingsLoaded");
            this.xml = new XML(this.urlLoader.data);
            var loc3:*=0;
            var loc4:*=this.xml.core.variable;
            for each (loc1 in loc4) 
            {
                net.bigpoint.flashcorelib.resources.SettingsHandler.getInstance().addVariable(loc1.attribute("key"), loc1.attribute("value"));
            }
            loc3 = 0;
            loc4 = this.xml.vars.variable;
            for each (loc1 in loc4) 
            {
                net.bigpoint.flashcorelib.resources.SettingsHandler.getInstance().addVariable(loc1.attribute("key"), loc1.attribute("value"));
            }
            logger.debug("debug 0");
            this.setCoreSettings();
            if (this.autoLoadRessources) 
            {
                logger.debug("debug 2");
                this.loadSkinSettings();
                logger.debug("debug 3");
            }
            else 
            {
                logger.debug("debug 4");
                if (this.autoLoadLanguage) 
                {
                    logger.debug("debug 5");
                    loc2 = net.bigpoint.flashcorelib.resources.LanguageHandler.getInstance();
                    loc2.addEventListener(net.bigpoint.flashcorelib.resources.LanguageHandlerEvent.LANGUAGELOADED, this.languageLoaded);
                    loc2.loadLanguage(this.languageXMLPath);
                }
                else 
                {
                    logger.debug("debug 6");
                    dispatchEvent(new net.bigpoint.flashcorelib.resources.SettingsHandlerEvent(net.bigpoint.flashcorelib.resources.SettingsHandlerEvent.SETTINGSLOADED));
                }
            }
            return;
        }

        internal function setCoreSettings():void
        {
            if (String(this.getVariable("autoLoadRessources")) == "true") 
            {
                this.autoLoadRessources = true;
            }
            if (String(this.getVariable("autoLoadLanguage")) == "true") 
            {
                this.autoLoadLanguage = true;
            }
            this.skinXMLPath = String(this.getVariable("skinXMLPath"));
            this.languageXMLPath = String(this.getVariable("languageXMLPath"));
            this.bitmapsPath = String(this.getVariable("bitmapsPath"));
            this.movieclipsPath = String(this.getVariable("movieclipsPath"));
            this.soundsPath = String(this.getVariable("soundsPath"));
            return;
        }

        internal function onErrorLoadSettings(arg1:flash.events.IOErrorEvent):void
        {
            logger.debug("onErrorLoadSettings");
            var loc1:*=new net.bigpoint.flashcorelib.resources.SettingsHandlerEvent(net.bigpoint.flashcorelib.resources.SettingsHandlerEvent.ERROR_LOADING_SETTINGS);
            loc1.setErrorMsg("could not load " + this.url);
            dispatchEvent(loc1);
            return;
        }

        internal function onErrorLoadSkinSettings(arg1:flash.events.IOErrorEvent):void
        {
            var loc1:*=new net.bigpoint.flashcorelib.resources.SettingsHandlerEvent(net.bigpoint.flashcorelib.resources.SettingsHandlerEvent.ERROR_LOADING_SETTINGS);
            loc1.setErrorMsg("could not load " + this.getVariable("skin_xml_path"));
            dispatchEvent(loc1);
            return;
        }

        public function getBitmapsPath():String
        {
            return this.bitmapsPath;
        }

        internal function skinSettingsLoaded(arg1:flash.events.Event):void
        {
            var loc1:*=null;
            var loc2:*=null;
            var loc3:*=null;
            var loc4:*=null;
            this.xml = new XML(this.urlLoader.data);
            var loc5:*=0;
            var loc6:*=this.xml.sounds.sound;
            for each (loc1 in loc6) 
            {
                this.addSoundKey(loc1.attribute("key"));
                this.addSoundFilename(loc1.attribute("filename"));
            }
            loc5 = 0;
            loc6 = this.xml.bitmaps.bitmap;
            for each (loc2 in loc6) 
            {
                this.addBitmapKey(loc2.attribute("key"));
                this.addBitmapFilename(loc2.attribute("filename"));
                this.addBitmapCategory(loc2.attribute("category"));
            }
            loc5 = 0;
            loc6 = this.xml.movieclips.movieclip;
            for each (loc3 in loc6) 
            {
                this.addMovieClipKey(loc3.attribute("key"));
                this.addMovieClipFilename(loc3.attribute("filename"));
            }
            if (this.autoLoadRessources) 
            {
                if (this.getBitmapKeys().length > 0) 
                {
                    net.bigpoint.flashcorelib.resources.MediaHandler.getInstance().addToQueue(net.bigpoint.flashcorelib.resources.MediaHandler.TYPE_BITMAP, this.getBitmapKeys(), this.getBitmapFilenames(), this.bitmapsPath);
                }
                if (this.getSoundKeys().length > 0) 
                {
                    net.bigpoint.flashcorelib.resources.MediaHandler.getInstance().addToQueue(net.bigpoint.flashcorelib.resources.MediaHandler.TYPE_SOUND, this.getSoundKeys(), this.getSoundFilenames(), this.soundsPath);
                }
                if (this.getMovieClipKeys().length > 0) 
                {
                    net.bigpoint.flashcorelib.resources.MediaHandler.getInstance().addToQueue(net.bigpoint.flashcorelib.resources.MediaHandler.TYPE_MOVIECLIP, this.getMovieClipKeys(), this.getMovieClipFilenames(), this.movieclipsPath);
                }
                if (!this.autoLoadLanguage) 
                {
                    dispatchEvent(new net.bigpoint.flashcorelib.resources.SettingsHandlerEvent(net.bigpoint.flashcorelib.resources.SettingsHandlerEvent.SETTINGSLOADED));
                }
            }
            if (this.autoLoadLanguage) 
            {
                (loc4 = net.bigpoint.flashcorelib.resources.LanguageHandler.getInstance()).addEventListener(net.bigpoint.flashcorelib.resources.LanguageHandlerEvent.LANGUAGELOADED, this.languageLoaded);
                loc4.loadLanguage(this.languageXMLPath);
            }
            return;
        }

        public function languageLoaded(arg1:net.bigpoint.flashcorelib.resources.LanguageHandlerEvent):void
        {
            dispatchEvent(new net.bigpoint.flashcorelib.resources.SettingsHandlerEvent(net.bigpoint.flashcorelib.resources.SettingsHandlerEvent.SETTINGSLOADED));
            return;
        }

        public function getVariable(arg1:String):Object
        {
            if (this.variables[arg1] == undefined) 
            {
                return null;
            }
            return this.variables[arg1];
        }

        public function addVariable(arg1:String, arg2:String):void
        {
            this.variables[arg1] = arg2;
            return;
        }

        public static const logger:mx.logging.ILogger=mx.logging.Log.getLogger("SettingsHandler");

        internal var variables:Array;

        internal var url:String;

        internal var bitmapFilenames:Array;

        internal var bitmapKeys:Array;

        internal var bitmapCategorys:Array;

        internal var movieClipFilenames:Array;

        internal var movieClipKeys:Array;

        internal var soundFilenames:Array;

        internal var soundKeys:Array;

        internal var autoLoadRessources:Boolean;

        internal var autoLoadLanguage:Boolean;

        internal var skinXMLPath:String;

        internal var languageXMLPath:String;

        internal var bitmapsPath:String;

        internal var movieclipsPath:String;

        internal var soundsPath:String;

        internal var urlRequest:flash.net.URLRequest;

        internal var urlLoader:flash.net.URLLoader;

        internal var xml:XML;

        internal static var instance:net.bigpoint.flashcorelib.resources.SettingsHandler=null;

        internal static var ALLOWINSTANTIATION:Boolean=false;

        internal var sharedObject:flash.net.SharedObject;
    }
}
