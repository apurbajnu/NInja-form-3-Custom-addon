<?php

// $pdf_elements = [
//     'logo', //image src
//     'logo_2',// image
//     'admin_sign',//image
//     'signer',
//     'account_id',
//     'form_data', //containg form data with 'ip'
//     'spouse',
//     'spouse_form_data',

// ];
function pdf_contents( $pdf_elements )
{
    ob_start(); ?>
<style>
    table,
    th,
    td {

        padding: 10px;
        font-size: 25px;
    }


    table {
        border-collapse: collapse;
        caption-side: bottom;
        width: 1200px;
        margin: 0 auto;
        table-layout: fixed;

    }




    caption {
        font-size: 20px;
        font-weight: bold;
        padding-top: 5px;
    }

    .site_logo {
        text-align: center;
    }

    .red {
        font-weight: bold;
        color: #7ea749;
        display: block;
    }

    .center-log {
        margin: 10px auto 30px;
        text-align: center;
    }

    hr {
        display: block;
        height: 1px;
        border: 0;
        border-top: 1px solid #7ea749;
        margin: 1em 0;
        padding: 0;
    }

    .signature {
        /*width: 250px;*/
        /*height: 100px;*/
    }

    .signature .sign {
        /*width:100%;*/
        /*height: 80px;*/
    }

    .extra-padding {
        padding: 50px 0;
    }

    .int-txt {
        text-align: center;
    }

    .small-txt {
        font-size: 20px;
        line-height: 0.8;
        color: #000;
    }

    .half-part {
        table-layout: fixed;

    }

    .half-part td {
        width: 50%;
        text-align: left;
        padding-bottom: 50px;
        padding-left: 125px;

    }

    .wrapper-page {
        position: relative;
        page-break-after: always;
    }

    .wrapper-page:last-child {
        page-break-after: avoid;
    }

    .end-page {
        position: relative;
    }

    .footer {
        position: absolute;
        width: 100%;
        bottom: 0;
        margin-bottom: 15px;
    }

    .footer>p {
        font-size: 18px;
    }
</style>
<div class="wrapper-page">
    <div class="center-log">
        <img width="250px" class="site_logo"
            src="<?php echo $pdf_elements['logo']; ?>"
            alt="Site Logo">
    </div>

    <div class="int-txt">
        <p style="font-weight:bold">Online Registration Form – <?php echo $pdf_elements['signer'] ?>
            <?php if ( !empty( $pdf_elements['spouse'] ) ): ?>
            and <?php echo $pdf_elements['spouse'] ?>
            <?php endif; ?>
        </p>
        <p>Signed on: <?php echo date( 'l dS \o\f F Y H:i:s' ) ?>
        </p>
        <br>
        <br>
        <br>
        <br>
        <p style="font-weight:bold">YourMoneyBack</p>

        <p class="small-txt">Document Sender: Trevor Kenny</p>
        <p class="small-txt">Signer: Trevor Kenny</p>
        <p class="small-txt">Signer: <?php echo $pdf_elements['signer'] ?>
        </p>
        <?php if ( !empty( $pdf_elements['spouse'] ) ): ?>
        <p class="small-txt">Signer: <?php echo $pdf_elements['spouse'] ?>
        </p>
        <?php endif; ?>
    </div>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>



</div>
<div class="wrapper-page">
    <?php
$element = array_chunk( $pdf_elements['form_data'], 2, true ); ?>

    <div class=" center-log">
        <img width="250px" class="site_logo"
            src="<?php echo $pdf_elements['logo']; ?>"
            alt="Site Logo">
    </div>


    <h3 class="center-log">Details for <?php echo $pdf_elements['signer'] ?>
    </h3>

    <table class="half-part">
        <?php $temp_data = [];
    $last_element = '' ?>
        <?php $inc = 1;
    foreach ( $element as $value ): ?>
        <?php if ( count( $value ) % 2 !== 0 ) {
        $last_element = array_slice( $value, -1, count( $value ), true );
    } ?>


        <?php foreach ( $value as $key => $innervalue ): ?>
        <?php $temp_data[] = $key;
    $temp_data[] = $innervalue; ?>
        <?php if ( $inc % 2 == 0 ): ?>

        <tr>
            <td>
                <span class="red"><?php echo $temp_data[0] ?>:</span><br><?php echo display_value( $temp_data[1] ); ?>
            </td>
            <td>
                <span class="red"><?php echo $temp_data[2] ?>:</span><br><?php echo display_value( $temp_data[3] ); ?>
            </td>
        </tr>

        <?php $temp_data = [];
    endif; ?>

        <?php if ( !empty( $last_element ) ): ?>
        <tr>
            <td>
                <span class="red"><?php echo key( $last_element ) ?>:</span><br><?php echo $last_element[key( $last_element )] ?>
            </td>
        </tr>

        <?php endif; ?>
        <?php $inc++;
    endforeach; ?>

        <?php endforeach; ?>

    </table>


</div>
<?php if ($pdf_elements['spouse'] !== null):?>
<div class="wrapper-page">
    <?php
$element = array_chunk( $pdf_elements['spouse_form_data'], 2, true ); ?>

    <div class=" center-log">
        <img width="250px" class="site_logo"
            src="<?php echo $pdf_elements['logo']; ?>"
            alt="Site Logo">

    </div>
    <h3 class="center-log">Details for <?php echo $pdf_elements['spouse'] ?>
    </h3>
    <table class="half-part">
        <?php $temp_data = [];
    $last_element = '' ?>
        <?php $inc = 1;
    $numItems = count( $element );
    $i = 0;
    foreach ( $element as $value ): ?>

        <?php if ( count( $value ) % 2 !== 0 ) {
        $last_element = array_slice( $value, -1, count( $value ), true );
    } ?>

        <?php foreach ( $value as $key => $innervalue ): ?>
        <?php $temp_data[] = $key;
    $temp_data[] = $innervalue; ?>
        <?php if ( $inc % 2 == 0 ): ?>

        <tr>
            <td>
                <span class="red"><?php echo $temp_data[0] ?>:</span><br><?php echo display_value( $temp_data[1] ); ?>
            </td>
            <td>
                <span class="red"><?php echo $temp_data[2] ?>:</span><br><?php echo display_value( $temp_data[3] ); ?>
            </td>
        </tr>


        <?php $temp_data = [];
    endif; ?>

        <?php if ( !empty( $last_element ) ): ?>

        <tr>
            <td>
                <span class="red"><?php echo key( $last_element ) ?>:</span><br><?php echo $last_element[key( $last_element )] ?>
            </td>
        </tr>

        <?php endif; ?>

        <?php $inc++;
    endforeach; ?>

        <?php endforeach; ?>


    </table>


</div>
<?php endif; ?>
<div class="wrapper-page">
    <div class="center-log">
        <img width="250px" class="site_logo"
            src="<?php echo $pdf_elements['logo']; ?>"
            alt="Site Logo">

    </div>

    <table>


        <tr>
            <td colspan="2">
                <h3><strong>1. Authorisation to Act as your Agent:</strong></h3>
                <br>
                <p>I, the undersigned, authorise YourMoneyBack (TAIN 76205U) with an address of Kilberry,
                    Athy, Co. Kildare, to act as my agent when dealing with all aspects of the filing of my Irish
                    income tax return, including the submission of refund or credit claims. I confirm that all
                    documentary evidence of entitlement to credits / reliefs claimed and taxable income sources,
                    will be held for a period of 6 years beginning at the end of the year of assessment to which
                    the
                    return of income and / or claim relates by either myself or YourMoneyBack. I confirm that
                    this authorisation will remain in force until Revenue is formally notified of its cessation by
                    either myself or YourMoneyBack, or for a maximum period of 4 years. the submission of an
                    updated Authorisation Form after 4 years will renew the authorisation.</p>
                <br>
                <h3><strong> 2. Authorisation for Agent to receive funds on behalf of Client</strong></h3>
                <br>
                <p>I authorise the transfer of any refund or repayment of PAYE/Income Levy/Universal Social
                    Charge due to me by the Revenue Commissioners by electronic funds transfer to the
                    following bank account which is held by YourMoneyBack. Note: It is not possible to make a
                    refund directly to a foreign bank account that is not a member of the Single Euro Payments
                    Area (SEPA).</p>
                <br>
                <table>
                    <tr>
                        <td>
                            <strong>Bank: Allied Irish Bank </strong>
                        </td>
                        <td>
                            <strong>Account Name: Your Money Back Client Account</strong>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>IBAN: IE52AIBK93310449681338</strong>
                        </td>
                        <td>
                            <strong>BIC: AIBKIE2D</strong>
                        </td>
                    </tr>
                </table>

                <br>
                <br>
                <p>I understand that any refund made by the Revenue Commissioners to my agent,
                    YourMoneyBack, on my behalf is refunded in a similar manner as if same were being
                    refunded directly to me and that once the refund is transferred into the bank account
                    nominated by me I have no further call upon the Revenue Commissioners in respect of
                    same.</p>
                <br>
                <p>I understand that YourMoneyBack is acting as my agent and is solely responsible to me in
                    respect of any refund received by them on my behalf. I further understand that my agent
                    YourMoneyBack is an independent entity and that the Revenue Commissioners make no
                    endorsement of my agent or any such agency and cannot accept any responsibility
                    whatsoever for problems encountered by me dealing with them.</p>
                <br>
                <p>I understand and agree that YourMoneyBack will input its own bank account details on the
                    Revenue records for the duration of this mandate and will remove these details on the
                    cessation of the mandate.</p>
                <br>
                <p>
                    I confirm that I am aware of, and agree to, the payment of the fees charged by
                    YourMoneyBack in respect of the services carried out on my behalf and that this fee will be
                    deducted from any amount refunded by Revenue and that the balance of this amount will
                    be paid to me.</p>
                <br>

            </td>
        </tr>

    </table>


</div>
<div class="end-page">
    <div class="center-log">
        <img width="250px" class="site_logo"
            src="<?php echo $pdf_elements['logo']; ?>"
            alt="Site Logo">

    </div>

    <table>


        <tr>
            <td colspan="2">
                <h3><strong> 3. Terms and Conditions of Authorisation</strong></h3>
                <br>
                <p>
                    I understand that Tax law provides for both civil penalties and criminal sanctions for the
                    failure to make a return, the making of a false return, facilitating the making of a false
                    return, or claiming tax credits, allowances or reliefs which are not due. I confirm that I will
                    provide the necessary documentation to YourMoneyBack to support any refund, credit
                    claims or claims for allowances and reliefs made to Revenue on my behalf by
                    YourMoneyBack. I confirm that I will provide details of all my sources of income to
                    YourMoneyBack. I understand that YourMoneyBack is required to retain all documentation
                    relating to any refund or credit or allowance or relief claimed by the agent on my behalf for
                    a period of 6 years beginning at the end of the year of assessment to which the Return of
                    Income and/or claim relates and that YourMoneyBack will be required to produce same to
                    Revenue upon request.
                </p>

            </td>
            <br>
        </tr>
        <tr>
            <td colspan="2"></td>
        </tr>

        <tr>
            <td>
                <span class="red">SIGNED (CLIENT):</span><br>
                <div class="signature">
                    <img height="80px" width="300px" class="sign"
                        src="<?php echo $pdf_elements['signs'][0]; ?>"
                        alt="client_sign">
                </div>
                <hr>
                <?php
                $name = $pdf_elements['signer']; ?>
                <span class="red"><?php echo $name ?></span><br>
                <span class="red"><?php echo date( 'l dS \o\f F Y H:i:s' ) ?></span>

            </td>
            <td>
                <span class="red">SIGNED (AGENT):</span><br>
                <div class="signature">
                    <img height="80px"
                        src="<?php echo $pdf_elements['admin_sign'] ?>"
                        alt="client_sign sign">
                </div>
                <hr>
                <span class="red">YourMoneyBack</span><br>
                <span class="red"><?php echo date( 'l dS \o\f F Y H:i:s' ) ?></span>
            </td>
        </tr>
        <?php if ( $pdf_elements['spouse'] !== null ):?>
        <tr>
            <td>
                <span class="red">SIGNED (CLIENT):</span><br>
                <div class="signature">
                    <img height="80px" width="300px" class="sign"
                        src="<?php echo $pdf_elements['signs'][1]; ?>"
                        alt="client_sign">
                </div>
                <hr>
                <?php
                $name = $pdf_elements['signer']; ?>
                <span class="red"><?php echo $pdf_elements['spouse'] ?></span><br>
                <span class="red"><?php echo date( 'l dS \o\f F Y H:i:s' ) ?></span>

            </td>
            <td>
                <span class="red">SIGNED (AGENT):</span><br>
                <div class="signature">
                    <img height="80px"
                        src="<?php echo $pdf_elements['admin_sign'] ?>"
                        alt="client_sign sign">
                </div>
                <hr>
                <span class="red">YourMoneyBack</span><br>
                <span class="red"><?php echo date( 'l dS \o\f F Y H:i:s' ) ?></span>
            </td>
        </tr>
        <?php endif; ?>
    </table>

    <table>
        <tr>
            <td colspan="3"><b>Signature Certification</b></td>
        </tr>

        <tr>
            <td><span class="red">Document Name:</span><br> <?php echo $pdf_elements['signer'] ?>
            </td>
            <td><span class="red">Document Signed On:</span><br> <?php echo date( 'l dS \o\f F Y H:i:s' ) ?>
            </td>
            <td><span class="red">IP:</span><br> <?php echo $pdf_elements['form_data']['ip']; ?>
            </td>
            <td><span class="red">Customer ID:</span><br> <?php echo $pdf_elements['account_id'] ?>
            </td>
        </tr>
    </table>

</div>
<?php

$output = ob_get_clean();

    return $output;
}

function display_value( $value )
{
    if ( is_array( $value ) ) {
        $str = '';
        $last_key = end( array_keys( $value ) );
        foreach ( $value as $key => $innerValue ) {
            if ( $key == $last_key ) {
                $str .= $innerValue;
                continue;
            }
            $str .= $innerValue . ' - ';
        }

        return $str;
    }

    return $value;
}

