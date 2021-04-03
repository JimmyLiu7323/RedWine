<?php

namespace App\Http\Middleware;

use Closure;
use App\AdminData;
use App\GroupData;

class VerifyMember
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //需驗證登入否(session)
        $MemberId=$request->session()->get('MemberId');
        if(!$MemberId){
            if($request->ajax()){
                return Response::make("",401);
            }
            else{
                return redirect('/login');
            }
        }
        else{
            return $next($request);
        }
    }
}
