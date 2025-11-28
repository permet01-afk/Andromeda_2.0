package
{
   import flash.display.MovieClip;
   
   [Embed(source="/_assets/assets.swf", symbol="symbol146")]
   public dynamic class rocketbuy extends MovieClip
   {
      
      public var rocketsSlot:MovieClip;
      
      public function rocketbuy()
      {
         super();
         addFrameScript(0,frame1);
      }
      
      internal function frame1() : *
      {
         stop();
      }
   }
}

