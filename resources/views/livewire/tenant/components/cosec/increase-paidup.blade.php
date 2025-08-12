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
        <br /><br /><br />
        
        <div style="text-align: center; font-weight: bold;">
            MEMBER’S WRITTEN RESOLUTION
        </div>
        
        <br />
        
        <div>
            THAT pursuant to Section 290(1) of the Companies Act, 2016, a Meeting of Member be convened and held by way of a Member’s Written Resolution for the purpose of considering and, if thought fit, passing the following resolution:-
        </div>
        
        <br />
        @php
            $data = json_decode($order->data, true);
            $totalShares = 0;
            foreach ($data['allottees'] as $allottee) {
                $totalShares += $allottee['unitShareToAllot'];
            }
        @endphp

        <div style="font-weight: bold;">
            <div>
                ORDINARY RESOLUTION
            </div>
            <div>
                    -	ALLOTMENT AND ISSUANCE OF {{ $totalShares }} NEW ORDINARY SHARES 
            </div>
        </div>

        <br />
        
        <div>
            THAT the Company be and is hereby authorised to allot and issue {{ $data['totalAmountToIncrease'] }} new Ordinary Shares in the share capital of the Company at the issue price of RM{{$data['pricePerShare']}} each to the following allottees, for a total consideration of RM{{ $data['pricePerShare'] * $data['totalAmountToIncrease']}} only
        </div>

        <br />
        
        <div>
            <table style="min-width: 100%;">
                <thead style="text-transform: uppercase;">
                    <tr style="background: #e2e2e2;">
                        <th style="font-weight: medium; min-width: 10rem">
                            Name of Allottees
                        </th>
                        <th style="font-weight: medium; min-width: 10rem">
                            Number of shares
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data['allottees'] as $allottee)
                    <tr>
                        <td>
                            {{ $allottee['name'] }}
                        </td>
                        <td style="text-align: center;">
                            {{ $allottee['unitShareToAllot'] }}
                        </td>
                    </tr>
                    @endforeach
                    <tr>
                        <td>
                            Total Shares
                        </td>
                        <td style="text-align: center; border-color: #242424; border-width: 1px;">
                            {{ $totalShares }}
                        </td>
                    </tr>

                </tbody>
            </table>
            
        </div>

        <br />
        
        <div>
            THAT the new Ordinary Shares when allotted shall be subject to the Companies Act 2016 and will rank for dividends to be declared as from the date of allotment thereof and in all respects pari passu with the existing Ordinary Shares of the Company.
        </div>
        
        <br/>

        <div style="font-weight: bold;">
            EXECUTION OF DOCUMENTS
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
        
        <div style="text-align: center; font-weight: bold;">
            MEMBER’S WRITTEN RESOLUTION
        </div>
        
        <br />
        
        <div>
            Pursuant to Section 290(1) of the Companies Act 2016, I, the undersigned, being the Member of the Company, for the time being entitled to receive notice of, and to attend and vote at general meetings of the Company, do hereby RESOLVED:-
        </div>
        
        <br />

        <div style="font-weight: bold;">
            <div>
                ORDINARY RESOLUTION
            </div>
            <div>
                 -	ALLOTMENT AND ISSUANCE OF {{ $totalShares }} NEW ORDINARY SHARES 
            </div>
        </div>

        <br />
        
        <div>
            THAT the Company be and is hereby authorised to allot and issue {{ $data['totalAmountToIncrease'] }} new Ordinary Shares in the share capital of the Company at the issue price of RM{{$data['pricePerShare']}} each to the following allottees, for a total consideration of RM{{ $data['pricePerShare'] * $data['totalAmountToIncrease']}} only
        </div>

        <br />

        <div>
            <table style="min-width: 100%;">
                <thead style="text-transform: uppercase;">
                    <tr style="background: #e2e2e2;">
                        <th style="font-weight: medium; min-width: 10rem">
                            Name of Allottees
                        </th>
                        <th style="font-weight: medium; min-width: 10rem">
                            Number of shares
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data['allottees'] as $allottee)
                    <tr>
                        <td>
                            {{ $allottee['name'] }}
                        </td>
                        <td style="text-align: center;">
                            {{ $allottee['unitShareToAllot'] }}
                        </td>
                    </tr>
                    @endforeach
                    <tr>
                        <td>
                            Total Shares
                        </td>
                        <td style="text-align: center; border-color: #242424; border-width: 1px;">
                            {{ $totalShares }}
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>

        <br />
        
        <div>
            THAT the new Ordinary Shares when allotted shall be subject to the Companies Act 2016 and will rank for dividends to be declared as from the date of allotment thereof and in all respects pari passu with the existing Ordinary Shares of the Company.
        </div>
        
        <br/>

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

        <div>
            MEMBER: - 
        </div>

        <br/> <br/> <br/>

        <div style="width: 50%;">
            <hr />
            <div style="text-align: center">
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
        <br />
        
        <div style="text-align: center; font-weight: bold;">
            Notes to the Member’s Written Resolution pursuant <br />
            to Section 290 of the Companies Act 2016
        </div>
        
        <hr/>
        <br />
        
        <div>
            1.	This resolution in writing has been proposed by the Director of the Company.
            <br /><br />
            2.	Please signify your agreement by signing the members’ written resolution at the place indicated.
            <br /><br />
            3.	If you signed the document and return it to the Company without indicating whether you agree to the resolution being passed, it will be assumed by the Company that you agreed to the resolution being passed.
            <br /><br />
            4.	If you return the document signed but without a date, it will be assumed by the Company that you have signed the document on the day preceding the day the document was received by the Company.
            <br /><br />
            5.	If not passed by the requisite majority of the total voting rights of eligible member(s), this resolution in writing shall lapse within 28 days from the date of circulation.
            <br /><br />
            6.	As the resolution is ordinary / special resolution, the requisite majority needed to pass the resolution is a simple majority / three-fourth of the total voting rights of all eligible member(s).
            <br /><br />
            7.	Once this resolution has been signed and return to the Company, your agreement to them may not be revoked.
            <br /><br />
            8.	Please return the signed document to the Company using one of the following methods:-
            <br /><br />
            <ul>
                <li>
                    deliver it by hand or send it by post to LSY Corporate Services Sdn Bhd of No. 24-1, Persiaran Puteri 1, Bandar Puteri Puchong, 47100 Puchong, Selangor Darul Ehsan; or
                </li>
                <li>
                    attach a scanned copy of the signed document to the following emails, enter “Written Resolution” in the subject line and send it to:-
                </li>
            </ul>
        </div>
        
        <br />

        <div style="font-weight: bold;">
            jane@lsyco.my <br/>
            secretary@lsyco.my
        </div>

        <br />
        <br/>
        <br/>
    </div>  
</div>
