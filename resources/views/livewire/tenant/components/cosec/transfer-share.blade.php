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
    
    <br />
    
    <div>
        I, the undersigned being the Sole Director of the Company for the time being, do and hereby RESOLVE the following resolutions in writing pursuant to Paragraph 15 of the Third Schedule of the Companies Act 2016:-
    </div>
    
    <br />

    <div style="font-weight: bold;">
        TRANSFER OF SHARES
    </div>

    <br />
    
    <div>
        THAT the following transfer of shares in the Company be and are hereby approved subject to the proper execution of the Forms of Transfer of Securities (Section 105 of Companies Act, 2016):-
    </div>

    <br />

    <div style="font-weight: bold;">
        NOTICE OF ADDRESS WHERE ACCOUNTING RECORDS ARE KEPT 
    </div>

    <br />
    
    <div>
        THAT the accounting records of the Company as required under section 245 of the Companies Act 2016 be kept at a place other than at the registered office be changed from OLD ADDRESS to NEW ADDRESS with immediate effect.
    </div>
    
    <br/>
    
    @php
        $data = json_decode($order->data, true);
    @endphp

    <div style="padding-left: 3rem; padding-right: 3rem">
        <table style="width: 100%">
            <tr>
                <td width="33%">
                    <div style="font-weight: bold; text-decoration: underline; margin-bottom: 0.5rem;">Transferor</div>
                    <div>
                        {{
                            $data['transferror_name']
                        }}
                    </div>
                </td>
                <td width="33%">
                    <div style="font-weight: bold; text-decoration: underline; margin-bottom: 0.5rem;">Transferee</div>
                    <div>
                        {{
                            $data['transferee_name']
                        }}
                    </div>
                </td>
                <td>
                    <div style="font-weight: bold; text-decoration: underline; margin-bottom: 0.5rem;">No. of Ordinary Shares</div>
                    <div>
                        {{ $data['sharesToTransfer'] }}
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <br />
    
    <div>
        THAT authority be and is hereby given to the Company Secretaries to notify the Companies Commission of Malaysia of the changes of the particulars in the Register of Members pursuant to Section 51 of the Companies Act 2016 and to do all such acts and things as may be necessary to give effect to the above transfer of shares. 
        <br/>
        <br/>
        AND THAT authority be and is hereby given for the affixation of the Common Seal of the Company onto the new share certificate (if any), to be issued to the transferee in connection with the above in accordance with the provisions of the Companies Act 2016.

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