<?php
class ViewDetector extends JModel
{
    /**
     * Code a logic to determine your views based on everything you want, for example user agent for mobile views.
     *
     * @param ApplicationController $App the whole application!
     * @version 1.03
     * @return view name
     */
    function DetermineView()
    {
    	if (!reg("jf/view/detector/enabled")) return "default";
        $this->App->LoadCustomSystemModule("plugin.useragent.mobile",".");
        $m=new UseragentMobilePlugin($this->App);
        $ua=$m->IsMobileUserAgent();
        $x=new jpRoot();
        $x=$x->__toString();
        if ($ua and file_exists($x."/app/view/mobile"))
            return "mobile";
        else
        {
            if (reg("jf/mode")=="system" and $x."/_japp/view/default") 
            {   
                return "default";
            }
            else
                return "default";
        }
    }
}
?>