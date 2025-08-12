<div style="margin-top: 48px; margin-bottom: 48px; margin-left: 32px; margin-right: 32px;">
    <div style="text-align: center; text-transform: uppercase; font-weight: bold;">
        {{ $order->company_name }}
    </div>
    <div style="text-align: center; text-transform: uppercase;">
        {{ $order->company_no }} ({{ $order->company_old_no }})
    </div>
    
    <div style="text-align: center;">
        (Incorporated in Malaysia)
    </div>
    <br /><br /><br />
    
    <div style="text-align: center; font-weight: bold;">
        DIRECTOR’S CIRCULAR RESOLUTION
    </div>
    
    <br />
    
    <div>
        We, the undersigned being the Directors of the Company for the time being, do and hereby RESOLVE the following resolutions in writing pursuant to Clause 121 of the Constitution of the Company:
    </div>
    
    <br />

    <div style="font-weight: bold;">
        REGISTRATION OF BRANCH ADDRESS
    </div>

    <br />
    
    @php
        $data = json_decode($order->data, true);
    @endphp
    
    <div>
        THAT the branch address of the Company be registered at {{ $data['fullAddress'] }} with immediate effect.
    </div>

    <br />

    <div style="font-weight: bold;">
        EXECUTION AND SUBMISSION OF DOCUMENTS
    </div>

    <br />
    
    <div>
        AND THAT the Company Secretaries be authorised to execute, sign and submit all necessary forms with the Companies Commission of Malaysia electronically or otherwise and to do all such acts, deeds and things as may be necessary to give effect to the above transactions and indemnified for any costs incurred by them in respect of any proceedings that relates to the liability for any act or omission in their capacity as an officer and in which judgment is given in their favour or in which they are acquitted or in which they are granted relief under the Companies Act 2016 or where proceedings are discontinued or not pursued.
    </div>
    
    <br/>

    <div>
        Dated this: 
    </div>
    
    <br/>

    <div style="text-align: center; font-weight: bold;">
        SOLE DIRECTOR / BOARD OF DIRECTORS
    </div>

    <br/> <br/> <br/>

    <div style="padding-left: 3rem; padding-right: 3rem">
        <table style="width: 100%;">
            <tr>
                <td width="50%" style="vertical-align: top;">
                    <div style="padding-left: 1.5rem; padding-right: 1.5rem;">
                        <hr style="color: #111111;"/>
                        <div>
                            Director <br/>
                            NAME
                        </div>
                    </div>
                </td>
                
                <td width="50%" style="vertical-align: top;">
                    <div style="padding-left: 1.5rem; padding-right: 1.5rem;">
                        <hr style="color: #111111;"/>
                        <div>
                            Director <br/>
                            NAME
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <br/>
    <br/>
</div> 
