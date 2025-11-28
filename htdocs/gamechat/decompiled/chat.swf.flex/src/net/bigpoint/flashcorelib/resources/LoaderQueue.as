package net.bigpoint.flashcorelib.resources 
{
    public class LoaderQueue extends Object
    {
        public function LoaderQueue(arg1:int, arg2:Array, arg3:Array, arg4:String)
        {
            super();
            this.mediatype = arg1;
            this.keys = arg2;
            this.filenames = arg3;
            this.directory = arg4;
            return;
        }

        public function getMediaType():int
        {
            return this.mediatype;
        }

        public function getKeys():Array
        {
            return this.keys;
        }

        public function getFilenames():Array
        {
            return this.filenames;
        }

        public function getDirectory():String
        {
            return this.directory;
        }

        internal var keys:Array;

        internal var filenames:Array;

        internal var directory:String;

        internal var mediatype:int;
    }
}
