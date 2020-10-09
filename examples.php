<?php
require_once 'vendor/autoload.php';
use \YaLinqo\Enumerable;
$examples = [
    //Any value is acceptable here
     "String value"
    ,[1] //you can return a literal array here
    ,["a" => 1, "b" => 2] //or a literal map
    ,(function(){ $v = "hello"; return [$v,$v,$v]; })() //or the result of a IIFE
    ,Enumerable::cycle([1,2])->take(5) //cycle repeats a sequence infinitely
    ,Enumerable::cycle(Enumerable::split("a_b_c_d_e","/_/"))->take(7)
    ,Enumerable::emptyEnum() //Enumerable with no values. Shortcut for Enumerable::from([])
    ,Enumerable::from([1, 2, 3])->where(function($v){ return $v % 2 == 0;}) //from converts an array or object to an Enumerable. where is like filter
    ,Enumerable::toInfinity(0)->take(5)//repeats a single value infinitely, like E::range(0,$infinity, 1), if $infinity was implemented as JavaScript's Infinity.
    ,Enumerable::toNegativeInfinity(0)->take(5) //like E::range(0,$infinity,-1)
    ,Enumerable::returnEnum(1) //shortcut for Enumerable::from([1])
    ,Enumerable::range(0,5,10) //the second param determines the number of values to return
    ,Enumerable::rangeDown(0,5,10) // same as Enumerable::range(0,5,-10)
    ,Enumerable::rangeTo(0,50,10) //the second param determins the upper limit of the values to return, not including the limit itself
    ,Enumerable::repeat(101)->take(4) //same as Enumerable::cycle([101]) and Enumerable::rangeTo(101,102,0) (if it were lazy, which it isn't)
    ,Enumerable::cycle([101])->take(4) //same as repeat(101)
    ,Enumerable::generate(function($v,$k){ return $v + 9; }, 1)->take(5) //similar to unfold (from f#) but here the generation goes on infinitely 
    ,Enumerable::generate(function($v,$k){ return $v * $v; }, 2)->take(5) //you can specify a seed value and then how to iterate the value 
    ,Enumerable::generate(function($v,$k){ return pow($v, .5); }, 65536)->take(5) 
    ,Enumerable::matches('at my face, against my chest, because of my trophy and my hat', '#(?<=my )\\w+#')
    ,Enumerable::repeat(1)->take(5)->toDictionary(function($v){ return $v; })
    ,Enumerable::from(["a"=>1,"b"=>2])->toKeys()
    ,Enumerable::range(1,10)->toLookup(function($v){ return $v % 2 == 0 ? "even" : "odd"; })
    ,Enumerable::range(1,10)->select(function($v,$k){ return "$v is at index $k"; })
    ,Enumerable::repeat(2)->take(3)->sum()
    ,Enumerable::from([["a"=>1], ["a"=>2], ["a"=>3]])->sum(function($v){ return $v["a"]; })
    ,Enumerable::from([1,"a",2,"b"])->ofType("string")
    ,Enumerable::from([1,"a",2,"b"])->ofType("integer")
    ,Enumerable::from([1,2,3,4])->cast("string")
    ,Enumerable::from([1,2,3,4])->cast("string")->cast("integer")
    ,Enumerable::from([1,2,3,1,2,3])
    ,Enumerable::from([1,2,3,1,2,3])->distinct()
    ,Enumerable::range(1,10)->groupBy(function($v){ return $v % 2; })
    ,Enumerable::from([1,2,3])->contains(3) ? "true" : "false"
    ,Enumerable::from([1,2,3])->contains(4) ? "true" : "false"
    ,Enumerable::from([1,2,3])->call(function($v,$k){ echo "(SIDE EFFECT for $k=>$v) ";})
    ,Enumerable::from([1,2,3,4])->average()
    ,Enumerable::from([["a"=>1],["a"=>2],["a"=>3],["a"=>4]])->average(function($v){ return $v['a']; })
    ,Enumerable::from([1,2,3,4,5])->any(function($v){ return $v % 5  == 0; }) ? "true" : "false"
    ,Enumerable::from([1,2,3,4  ])->any(function($v){ return $v % 5  == 0; }) ? "true" : "false"
    ,Enumerable::from([1,2,3,4,5])->all(function($v){ return $v > 0; }) ? "true" : "false"
    ,Enumerable::from([1,2,3,4,5])->all(function($v){ return $v > 1; }) ? "true" : "false"
    ,Enumerable::from([1,1,1,2,2,2])->count()
    ,Enumerable::from([1,1,1,2,2,2])->count(function($v){ return $v == 2; })
    ,Enumerable::from([1,2,3])->intersect([3,4,5,6])
    ,Enumerable::from([1,2,3])->intersect([3,4,5,6], function($v,$k){ return $k; })
    ,Enumerable::from([1,2,3])->max()
    ,Enumerable::from([1,2,3])->min()
    ,Enumerable::from([2,3,1])->orderBy(function($v){ return $v; })
    ,Enumerable::from([2,3,1])->orderBy(function($v){ return -$v; })
    ,Enumerable::from([2,3,1])->orderByDescending(function($v){ return $v; })
    ,Enumerable::from([2,3,1])->orderByDescending(function($v){ return -$v; })
    ,Enumerable::from([2,3,1])->aggregate(function($a,$v,$k){ return max($a,$v); }, 0)
    ,Enumerable::from([2,3,1])->aggregate(function($a,$v,$k){ return min($a,$v); }, 0)
    ,Enumerable::emptyEnum()->aggregate(function($a,$v,$k){ return min($a,$v); }, 1999)
    //BROKEN:,Enumerable::concat( Enumerable::from([1]), Enumerable::from([2,3]))
    //BROKEN:,Enumerable::from([1])->concat(Enumerable::from([2,3]))
    //BROKEN:,Enumerable::from([1,2,3]).union([3,4,5,6])
    //BROKEN:,Enumerable::union([1,2,3],[3,4,5,6])
    ,Enumerable::from([1,2,3])->except([3,4,5]) //set operation: first minus second
    ,Enumerable::from([1,2,3])->intersect([3,4,5])->toValues() //set operation, returns a map for some reason
    //BROKEN,Enumerable::from([1])->prepend(0)
    //BROKEN,Enumerable::from([1])->append(2)
    ,Enumerable::range(1,10)->skipWhile(function($v){ return $v < 5; })->toValues()// returns a map for some reason
    ,Enumerable::from(["jake","make","take"])->indexOf("make")
    ,Enumerable::range(1,3)->toString("|")
    ,(function(){
        $a = [["x"=>1,"y"=>1], ["x"=>2,"y"=>1], ["x"=>3,"y"=>1]];
        $b = [["x"=>1,"y"=>2], ["x"=>2,"y"=>2], ["x"=>3,"y"=>2]];
        //return from($a).join($b);
        return "JOIN is BROKEN";
    })()
    ,(function(){
        $a = [["x"=>1,"y"=>1], ["x"=>2,"y"=>1], ["x"=>3,"y"=>1]];
        $b = [["x"=>1,"y"=>2], ["x"=>2,"y"=>2], ["x"=>3,"y"=>2]];
        //return from($a).concat($b);
        return "CONCAT is BROKEN";
    })()
];

$example_number=1;
$padlen = strlen(strval(count($examples)));
foreach($examples as $e){
    $padded_example_number = str_pad($example_number, $padlen, " ", STR_PAD_LEFT);
    echo "Example $padded_example_number:\t";
    if($e instanceof Enumerable){
        echo $e->toJson();
    } else if (is_scalar($e)){
        echo "$e";
    } else {
        echo Enumerable::from($e)->toJson();
    }
    echo "\n";
    $example_number++;
}
?>