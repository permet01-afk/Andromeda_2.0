package net.bigpoint.darkorbit.fooskater.kabelsalat
{
   import flash.utils.ByteArray;
   
   public class KabelSpeicher
   {
      
      var _temp_1:* = false;
      var _loc1_:Boolean = true;
      var _loc2_:Boolean = _temp_1;
      
      private const §_-7§:uint = 47;
      
      private const §_-6§:uint = 254;
      
      private const §_-3§:uint = 157;
      
      public var pos:int = 0;
      
      private var §_-5§:ByteArray;
      
      public function KabelSpeicher(param1:ByteArray)
      {
         var _temp_1:* = false;
         var _loc2_:Boolean = true;
         var _loc3_:Boolean = _temp_1;
         super();
         this.§_-5§ = param1;
      }
      
      public function connect(param1:int) : void
      {
         var _temp_1:* = true;
         var _loc2_:Boolean = false;
         var _loc3_:Boolean = _temp_1;
         this.pos = param1;
      }
      
      public function readState() : Boolean
      {
         var _temp_1:* = false;
         var _loc1_:Boolean = true;
         var _loc2_:Boolean = _temp_1;
         return true;
      }
      
      public function send(param1:String, param2:String = null) : void
      {
         var _temp_1:* = true;
         var _loc8_:* = false;
         var _loc9_:* = _temp_1;
         §§push(0);
         if(!_loc9_)
         {
            §§push(§§pop() * 111 - 1 + 27 - 24);
         }
         var _loc5_:* = §§pop();
         §§push(0);
         if(_loc8_)
         {
            §§push(-(-(-(§§pop() - 1) - 1) + 36));
         }
         var _loc6_:* = §§pop();
         §§push(0);
         if(!_loc9_)
         {
            §§push(--§§pop() * 52 * 14 - 1 - 36);
         }
         var _loc7_:* = §§pop();
         this.§_-B§();
         var _loc3_:* = uint(this.pos);
         §§push(0);
         if(_loc8_)
         {
            §§push(§§pop() + 1 + 1 + 1);
         }
         var _loc4_:* = §§pop();
         while(_loc4_ < param1.length)
         {
            if(_loc8_)
            {
               var _temp_3:* = _loc5_;
               var _temp_2:* = _loc4_;
               _loc5_ = _loc5_;
               _loc4_ = _temp_2;
               _loc5_ = _temp_3;
            }
            _loc5_ = param1.charCodeAt(_loc4_);
            if(!_loc9_)
            {
               var _temp_5:* = param1;
               var _temp_4:* = _loc9_;
               _loc7_ = _loc7_;
               _loc9_ = _temp_4;
               param1 = _temp_5;
               while(true)
               {
                  _loc3_++;
                  if(!_loc8_)
                  {
                     break;
                  }
                  var _temp_7:* = this;
                  var _temp_6:* = _loc6_;
                  param2 = param2;
                  _loc6_ = _temp_6;
                  this = _temp_7;
               }
               addr00cf:
               _loc4_++;
               if(_loc9_)
               {
               }
               continue;
               addr0097:
            }
            while(true)
            {
               this.§_-5§[_loc3_] = _loc5_ - 47;
               if(!_loc9_)
               {
                  var _temp_9:* = _loc7_;
                  var _temp_8:* = _loc3_;
                  param2 = param2;
                  _loc3_ = _temp_8;
                  _loc7_ = _temp_9;
                  break;
               }
               §§goto(addr0097);
            }
            §§goto(addr00cf);
         }
         if(param2)
         {
            §§push(0);
            if(_loc8_)
            {
               §§push(§§pop() + 1 - 60 - 117 - 1);
            }
            _loc6_ = §§pop();
            while(_loc6_ < param2.length)
            {
               if(_loc8_)
               {
                  var _temp_11:* = param1;
                  var _temp_10:* = _loc5_;
                  _loc3_ = _loc3_;
                  _loc5_ = _temp_10;
                  param1 = _temp_11;
                  while(true)
                  {
                     _loc3_++;
                     if(_loc9_)
                     {
                        break;
                     }
                     var _temp_13:* = _loc9_;
                     var _temp_12:* = _loc9_;
                     _loc7_ = _loc7_;
                     _loc9_ = _temp_12;
                     _loc9_ = _temp_13;
                  }
                  addr016b:
                  _loc6_++;
                  if(!_loc9_)
                  {
                     var _temp_15:* = param2;
                     var _temp_14:* = _loc9_;
                     _loc8_ = _loc8_;
                     _loc9_ = _temp_14;
                     param2 = _temp_15;
                  }
                  continue;
                  addr0110:
               }
               while(true)
               {
                  _loc7_ = param2.charCodeAt(_loc6_);
                  if(_loc8_)
                  {
                     var _temp_17:* = _loc5_;
                     var _temp_16:* = _loc3_;
                     _loc5_ = _loc5_;
                     _loc3_ = _temp_16;
                     _loc5_ = _temp_17;
                  }
                  this.§_-5§[_loc3_] = _loc7_ - 47;
                  if(!_loc9_)
                  {
                     var _temp_19:* = param1;
                     var _temp_18:* = this;
                     _loc8_ = _loc8_;
                     this = _temp_18;
                     param1 = _temp_19;
                     break;
                  }
                  §§goto(addr0110);
               }
               §§goto(addr016b);
            }
            if(_loc9_)
            {
            }
         }
         this.§_-5§[_loc3_] = 157;
         this.§_-8§();
      }
      
      public function receiveValue(param1:uint = 0) : String
      {
         var _temp_1:* = true;
         var _loc2_:Boolean = false;
         var _loc3_:Boolean = _temp_1;
         §§push(param1);
         §§push(0);
         if(!_loc3_)
         {
            §§push(§§pop() + 109 - 70 + 38);
         }
         if(§§pop() == §§pop())
         {
            return this.§_-4§(this.§_-5§,this.pos);
         }
         return this.§_-4§(this.§_-5§,param1);
      }
      
      private function §_-4§(param1:ByteArray, param2:uint, param3:uint = 256) : String
      {
         var _temp_1:* = true;
         var _loc7_:Boolean = false;
         var _loc8_:Boolean = _temp_1;
         §§push(0);
         if(_loc7_)
         {
            §§push(-(§§pop() - 78 - 1) - 61 - 24 - 53);
         }
         var _loc6_:* = §§pop();
         this.§_-B§();
         var _loc4_:String = "";
         §§push(0);
         if(!_loc8_)
         {
            §§push(-§§pop() + 37 + 1);
         }
         var _loc5_:* = §§pop();
         while(_loc5_ < param3)
         {
            _loc6_ = int(param1[param2]);
            if(_loc6_ == 157)
            {
               break;
            }
            _loc4_ += String.fromCharCode(_loc6_ + 47);
            param2++;
            _loc5_++;
         }
         this.§_-8§();
         return _loc4_;
      }
      
      public function clear(param1:uint = 256) : void
      {
         var _temp_1:* = true;
         var _loc6_:Boolean = false;
         var _loc7_:Boolean = _temp_1;
         §§push(0);
         if(_loc6_)
         {
            §§push(-(--(§§pop() - 1 + 7) * 5 + 89));
         }
         var _loc3_:* = §§pop();
         this.§_-B§();
         §§push(0);
         if(_loc6_)
         {
            §§push((§§pop() + 1 - 1) * 73 - 1 + 1);
         }
         var _loc2_:* = §§pop();
         while(_loc2_ < param1)
         {
            ++this.pos;
            _loc3_ = int(this.§_-5§[this.pos]);
            §§push(_loc3_);
            §§push(255);
            if(!_loc7_)
            {
               §§push(§§pop() + 58 + 116 - 1 - 1 - 35 + 1 + 1);
            }
            if(§§pop() == §§pop())
            {
               §§push(this.§_-5§);
               §§push(this.pos);
               §§push(Math.random());
               §§push(255);
               if(!_loc7_)
               {
                  §§push(§§pop() * 33 - 1 + 1 - 1 - 1);
               }
               §§push(§§pop() * §§pop());
               §§push(255);
               if(!_loc7_)
               {
                  §§push(§§pop() * 65 - 103 + 118 + 23);
               }
               §§pop()[§§pop()] = §§pop() & §§pop();
               break;
            }
            §§push(this.§_-5§);
            §§push(this.pos);
            §§push(Math.random());
            §§push(255);
            if(_loc6_)
            {
               §§push(-(§§pop() * 94 * 84));
            }
            §§push(§§pop() * §§pop());
            §§push(255);
            if(_loc6_)
            {
               §§push(§§pop() + 1 - 1 - 1);
            }
            §§pop()[§§pop()] = §§pop() & §§pop();
            _loc2_++;
         }
         this.§_-8§();
      }
      
      private function §_-B§() : void
      {
         var _temp_1:* = true;
         var _loc3_:Boolean = false;
         var _loc4_:Boolean = _temp_1;
         try
         {
            this.§_-5§.inflate();
         }
         catch(e:Error)
         {
         }
      }
      
      private function §_-8§() : void
      {
         var _temp_1:* = true;
         var _loc3_:Boolean = false;
         var _loc4_:Boolean = _temp_1;
         try
         {
            this.§_-5§.deflate();
         }
         catch(e:Error)
         {
         }
      }
   }
}

