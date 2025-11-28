package net.bigpoint.flashcorelib.resources 
{
    import flash.events.*;
    import flash.net.*;
    
    public class LanguageHandler extends flash.events.EventDispatcher
    {
        public function LanguageHandler()
        {
            this.variables = new Array();
            this.proxy = new net.bigpoint.flashcorelib.resources.LanguageHandlerProxy();
            super();
            if (!ALLOWINSTANTIATION) 
            {
                throw new Error("Error: Instantiation failed: Use getInstance() instead of new.");
            }
            return;
        }

        public function loadLanguage(arg1:String):void
        {
            this.urlRequest = new flash.net.URLRequest(arg1);
            this.urlLoader = new flash.net.URLLoader();
            this.urlLoader.addEventListener(flash.events.Event.COMPLETE, this.languageLoaded);
            this.urlLoader.addEventListener(flash.events.IOErrorEvent.IO_ERROR, this.errorLoadingLanguage);
            this.urlLoader.load(this.urlRequest);
            return;
        }

        internal function errorLoadingLanguage(arg1:flash.events.Event):void
        {
            return;
        }

        internal function languageLoaded(arg1:flash.events.Event):void
        {
            var loc1:*=null;
            var loc2:*=null;
            var loc3:*=null;
            this.xml = new XML(this.urlLoader.data);
            var loc4:*=0;
            var loc5:*=this.xml;
            for each (loc1 in loc5) 
            {
                var loc6:*=0;
                var loc7:*=loc1.item;
                for each (loc3 in loc7) 
                {
                    this.addVariable(loc3.attribute("name"), loc3.valueOf());
                }
            }
            loc2 = new net.bigpoint.flashcorelib.resources.LanguageHandlerEvent(net.bigpoint.flashcorelib.resources.LanguageHandlerEvent.LANGUAGELOADED);
            dispatchEvent(loc2);
            return;
        }

        public function getWord(arg1:String):String
        {
            if (this.proxy.variables[arg1] != undefined) 
            {
                return String(this.proxy.variables[arg1]);
            }
            if (this.variables[arg1] == undefined) 
            {
                return "";
            }
            return String(this.variables[arg1]);
        }

        internal function addVariable(arg1:String, arg2:String):void
        {
            this.variables[arg1] = arg2;
            return;
        }

        public static function getInstance():net.bigpoint.flashcorelib.resources.LanguageHandler
        {
            if (instance == null) 
            {
                ALLOWINSTANTIATION = true;
                instance = new LanguageHandler();
                ALLOWINSTANTIATION = false;
            }
            return instance;
        }

        
        {
            instance = null;
            ALLOWINSTANTIATION = false;
        }

        internal var variables:Array;

        internal var urlRequest:flash.net.URLRequest;

        internal var urlLoader:flash.net.URLLoader;

        internal var xml:XML;

        internal var proxy:net.bigpoint.flashcorelib.resources.LanguageHandlerProxy;

        internal static var instance:net.bigpoint.flashcorelib.resources.LanguageHandler=null;

        internal static var ALLOWINSTANTIATION:Boolean=false;
    }
}
