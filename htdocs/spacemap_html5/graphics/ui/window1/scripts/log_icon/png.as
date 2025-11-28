package log_icon
{
   import flash.display.BitmapData;
   
   [Embed(source="/_assets/16_log_icon.png.png")]
   public dynamic class png extends BitmapData
   {
      
      public function png(param1:int = 24, param2:int = 24)
      {
         super(param1,param2);
      }
   }
}

