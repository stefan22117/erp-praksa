<table id="background-table" cellpadding="0" cellspacing="0" border="0" width="100%">
	<tbody><tr>
		<td align="center" bgcolor="#ececec">
        	<table class="w640" style="margin:0 10px;" cellpadding="0" cellspacing="0" border="0" width="640">
            	<tbody><tr><td class="w640" height="20" width="640"></td></tr>
                
            	<tr>
                	<td class="w640" width="640">
                        <table id="top-bar" class="w640" cellpadding="0" cellspacing="0" bgcolor="#425470" border="0" width="640">
    <tbody><tr height="40px">
        <td class="w15" width="15"></td>
        <td class="w325" align="left" valign="middle" width="350">
           Feedback
        </td>
        <td class="w30" width="30"></td>
        <td class="w255" align="right" valign="middle" width="255">
            <table class="w255" cellpadding="0" cellspacing="0" border="0" width="255">
                <tbody><tr><td class="w255" height="8" width="255"></td></tr>
            </tbody></table>
            <table cellpadding="0" cellspacing="0" border="0">
    <tbody><tr>
        
        
        
    </tr>
</tbody></table>
            <table class="w255" cellpadding="0" cellspacing="0" border="0" width="255">
                <tbody><tr><td class="w255" height="8" width="255"></td></tr>
            </tbody></table>
        </td>
        <td class="w15" width="15"></td>
    </tr>
</tbody></table>
                        
                    </td>
                </tr>
                <tr>
                <td id="header" class="w640" align="center" bgcolor="#425470" width="640">
    
    <table class="w640" cellpadding="0" cellspacing="0" border="0" width="640">
        <tbody>
        <tr>
            <td class="w30" width="30"></td>
            <td class="w580" width="580">
                
            </td>
            <td class="w30" width="30"></td>
        </tr>
    </tbody></table>
    
    
</td>
                </tr>
                
                <tr><td class="w640" bgcolor="#ffffff" height="30" width="640"></td></tr>
                <tr id="simple-content-row"><td class="w640" bgcolor="#ffffff" width="640">
    <table class="w640" cellpadding="0" cellspacing="0" align="left" border="0" width="640">
        <tbody><tr>
            <td class="w30" width="30"></td>
            <td class="w580" width="580">
                <repeater>
                    <layout label="Text only">
                        <table class="w580" cellpadding="0" cellspacing="0" border="0" width="580">
                            <tbody><tr>
                                <td class="w580" width="580">
                                    <div class="article-content" align="left">
                                        <multiline label="Description"><b>User:</b> <?php echo $data['Feedback']['name']; ?></multiline>
                                    </div>
                                </td>
                            </tr>
                            <tr><td class="w580" height="10" width="580"></td></tr>
                        </tbody></table>
                    </layout>
                    <layout label="Text only">
                        <table class="w580" cellpadding="0" cellspacing="0" border="0" width="580">
                            <tbody><tr>
                                <td class="w580" width="580">
                                    <div class="article-content" align="left">
                                        <multiline label="Description"><b>Link: </b> <a href="<?php echo $data['Feedback']['link']; ?>"  style="text-decoration:underline; color:blue;" title="<?php echo $data['Feedback']['link']; ?>"><?php echo $data['Feedback']['short_link']; ?></a></multiline>
                                    </div>
                                </td>
                            </tr>
                            <tr><td class="w580" height="10" width="580"></td></tr>
                        </tbody></table>
                    </layout>
                    
                    <layout label="Text only">
                        <table class="w580" cellpadding="0" cellspacing="0" border="0" width="580">
                            <tbody><tr>
                                <td class="w580" width="580">
                                    <b>Feedback:</b><br/>
                                    <div class="article-content" align="left">
                                        <multiline label="Description"><?php echo $data['Feedback']['description']; ?></multiline>
                                        <br/>
                                        <br/>
                                        <multiline label="Description" style="text-decoration:underline;">
                                        <a href="http://www.mikroe.com/erp/process/Feedbacks/view/<?php echo $data['Feedback']['id']; ?>" style="color:blue;">Pogledajte Feedback</a>
                                        </multiline>
                                    </div>
                                </td>
                            </tr>
                            <tr><td class="w580" height="10" width="580"></td></tr>
                        </tbody></table>
                    </layout>
                </repeater>
            </td>
            <td class="w30" width="30"></td>
        </tr>
    </tbody></table>
</td></tr>
    <tr><td class="w640" bgcolor="#ffffff" height="15" width="640"></td></tr>
        <tr>
            <td class="w640" width="640">
                <table id="footer" class="w640" cellpadding="0" cellspacing="0" bgcolor="#425470" border="0" width="640">
                    <tbody>
                    <tr>
                        <td class="w30" width="30"></td>
                        <td class="w580" valign="top" width="360">
                        <span class="hide"><p id="permission-reminder" class="footer-content-left" align="left"></p></span>
                        <p class="footer-content-left" align="left"><preferences lang="en">Poslato sa mikroERP informacionog sistema</preferences> </p>
                        </td>
                        <td class="hide w0" width="60"></td>
                        <td class="hide w0" valign="top" width="160">
                        <p id="street-address" class="footer-content-right" align="right"></p>
                        </td>
                        <td class="w30" width="30"></td>
                    </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr><td class="w640" height="60" width="640"></td></tr>
    </tbody></table>
        </td>
	</tr>
</tbody>
</table>