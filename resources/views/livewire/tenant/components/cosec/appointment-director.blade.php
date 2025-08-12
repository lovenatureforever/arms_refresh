<style>
.page-break {
    page-break-after: always;
}
</style>
<div>
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
        <br />
        
        <div style="text-align: center; font-weight: bold;">
            DIRECTOR’S CIRCULAR RESOLUTION
        </div>
        
        <br /><br /><br />
        
        <div>
            I, the undersigned being the sole Director of the Company for the time being, do and hereby RESOLVE the following resolution in writing pursuant to Paragraph 15 of the Third Schedule of the Companies Act 2016:-
        </div>
        
        <br />

        <div style="font-weight: bold;">
            APPOINTMENT OF DIRECTOR
        </div>

        <br />
        
        @php
            $data = json_decode($order->data, true);
        @endphp

        <div>
            THAT {{ $data['name'] }} (NRIC No.: {{ $data['passport'] }}) be and is hereby appointed as Director of the Company subject to the completion of her respective Statutory Declaration in compliance with Section 201 of the Companies Act, 2016 with immediate effect.
        </div>

        <br />

        <div style="font-weight: bold;">
            EXECUTION OF DOCUMENTS
        </div>

        <br />
        
        <div>
            THAT the Company Secretaries be authorised to sign and submit all necessary e-Forms with the Companies Commission of Malaysia electronically or otherwise and to do all such acts, deeds and things as may be necessary to give effect to the above resolution for and on behalf of the Company.
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

        <div style="padding-left: 1.5rem; padding-right: 1.5rem;">
            <hr style="color: #111111;"/>
            <div style="text-align: center;">
                Director <br/>
                NAME
            </div>
        </div>
        
        <br/>
        <br/>
    </div> 

    <div class="page-break"></div>

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
        
        <div>
            Certified Extract of the Director’s Circular Resolution passed pursuant to Paragraph 15 of the Third Schedule of the Companies Act 2016:-
        </div>
        
        <br />

        <div style="font-weight: bold;">
            APPOINTMENT OF DIRECTOR
        </div>

        <br />
        
        <div>
            THAT NEW DIR’S NAME (NRIC No.: XXX) be and is hereby appointed as Director of the Company subject to the completion of her respective Statutory Declaration in compliance with Section 201 of the Companies Act, 2016 with immediate effect.
        </div>

        <br />

        <div style="font-weight: bold;">
            EXECUTION OF DOCUMENTS
        </div>

        <br />
        
        <div>
            THAT the resignation of RESIGNED DIR NAME (NRIC No.: XXX) as the Director of the Company with immediate effect be and is hereby accepted with regret and a vote of thanks be recorded for his past services rendered to the Company.
        </div>

        <br/>
    
        <div>
            Dated this: 
        </div>
        
        <br/>

        <div style="text-align: center; font-weight: bold;">
            CERTIFIED TRUE COPY:-
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
                                Company Secretary <br/>
                                CO SEC NAME <br />
                                CO SEC LICENSE NUM <br />
                                SSM PC NO. XXX <br />
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <br/>
        <br/>
    </div>               
</div>