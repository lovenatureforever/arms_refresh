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
        I, the undersigned being the Sole Director of the Company for the time being, do and hereby RESOLVE the following resolution in writing pursuant to Paragraph 15 of the Third Schedule of the Companies Act 2016.
    </div>
    
    <br />

    <div style="font-weight: bold;">
        REGISTRATION OF BUSINESS ADDRESS 
    </div>

    <br />

    @php
        $data = json_decode($order->data, true);
    @endphp
    
    <div>
        THAT the business address of the Company be registered at {{ $data['fullAddress'] }} with immediate effect.
    </div>

    <br />

    <div style="font-weight: bold;">
        NOTICE OF ADDRESS WHERE ACCOUNTING RECORDS ARE KEPT 
    </div>

    <br />
    
    <div>
        THAT the accounting records of the Company as required under section 245 of the Companies Act 2016 be kept at a place other than at the registered office at {{ $data['fullAddress']}} with immediate effect.
    </div>
    
    <br/>

    <div style="font-weight: bold;">
        EXECUTION AND SUBMISSION OF DOCUMENTS TO SSM
    </div>

    <br />
    
    <div>
        THAT the Company Secretaries be authorised to execute, sign and submit all necessary forms with the Companies Commission of Malaysia electronically or otherwise and to do all such acts, deeds and things as may be necessary to give effect to the above transactions and indemnified for any costs incurred by them in respect of any proceedings that relates to the liability for any act or omission in their capacity as an officer and in which judgment is given in their favour or in which they are acquitted or in which they are granted relief under the Companies Act 2016 or where proceedings are discontinued or not pursued.
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

    <div style="width: 50%; margin: auto;">
        <hr />
        <div style="text-align: center">
            Director <br/>
            NAME
        </div>
    </div>

    <br/>
    <br/>
</div>              
