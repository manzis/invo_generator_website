<?php

session_start();

if (!isset($_SESSION['user']) || !isset($_SESSION['user']['user_id'])) {
  // User is not logged in, redirect to the login page
  header("Location: ../loginpage/loginsignup.php");
  exit;

}

$userID = $_SESSION['user']['user_id'];


?>




<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Create me a Invoice</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Helvetica:wght@400;700&display=swap" />
  <link rel="stylesheet" href="index.css" />
  <link rel="stylesheet" href="nav.css">


  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.5.0-beta4/html2canvas.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.70/pdfmake.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.70/vfs_fonts.js"></script>

</head>

<body>
<?php include "nav.php" ?>
  <div class="main-container">
    
    <div class="frame-8">
      <div class="frame-9">
        <div class="frame-a">
          <div class="frame-b">
            <button class="frame-c" id="uploadWrapper">
              <div class="frame-e">
                <img class="previewimage" id="previewImage" src="" alt="Selected Image">
                <div class="logo-add" id="logo-add">
                  <div class="plus-sign">
                    <div class="vector-d"></div>
                  </div>
                  <span class="add-a-logo"> Add a Logo</span>
                </div>
              </div>
            </button>
            <input class="inputfile" type="file" id="fileInput" accept="image/*" name="company_logo" style="display:none;">
            <div class="frame-f">
              <textarea class="who-is-this-from" type="text" id="inputField1" placeholder="Who is this From ?" name="invoice_from"></textarea>
            </div>
            <div class="frame-10">
              <div class="frame-11">
                <div class="frame-12">
                  <span class="bill-to">Bill To </span>
                </div>
                <div class="frame-13">
                  <textarea class="who-is-to" placeholder="Who is this to ?" id="inputField2" name="invoice_to"></textarea>
                </div>
              </div>
              <div class="frame-15">
                <div class="frame-16">
                  <span class="ship-to">Ship To </span>
                </div>
                <div class="frame-17">
                  <textarea name="ship_to" class="optional" placeholder="(optional)"></textarea>
                </div>
              </div>
            </div>
          </div>
          <div class="frame-18">
            <div class="frame-19">
              <div class="frame-1a"><span class="invoice">INVOICE</span></div>
              <div class="frame-1b">
                <div class="frame-1c"><span class="number">#</span></div>
                <div class="frame-1d">
                  <input name="invoice_number" class="number-1e" value="1234">
                </div>
              </div>
            </div>
            <div class="frame-1f">
              <div class="frame-20">
                <div class="frame-21"><span class="date">Date</span></div>
                <div class="frame-22">
                  <input class="date-sep" name="inv_date" type="date" required>
                </div>
              </div>
              <div class="frame-24">
                <div class="frame-25">
                  <span class="payments-terms">Payments Terms</span>
                </div>
                <div class="frame-26">
                  <input class="prepaid" name="payment_terms" placeholder="Prepaid/Cash" type="text">
                </div>
              </div>
              <div class="frame-28">
                <div class="frame-29">
                  <span class="due-date">Due Date</span>
                </div>
                <div class="frame-2a">
                  <input class="date-oct" name="inv_due_date" type="date">
                </div>
              </div>
              <div class="frame-2b">
                <div class="frame-2c">
                  <span class="contact-details">Contact Details</span>
                </div>
                <div class="frame-2d">
                  <input type="text" name="inv_contact" class="text-info">
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="frame-2e">
          <div class="frame-2f">
            <div class="frame-30"><span class="item">Item</span></div>
            <div class="frame-31">
              <div class="frame-32">
                <span class="quantity">Quantity </span>
              </div>
              <div class="frame-33"><span class="rate">Rate</span></div>
              <div class="frame-34"><span class="amount">Amount</span></div>
            </div>
          </div>
          <div class="billcontainers" id="billcontainers">

            <div class="frame-35">

              <div class="frame-36">
                <input type="text" name="item_list" class="description-text" placeholder="Description of item /service...">
              </div>
              <div class="frame-37">
                <input class="text-info-38" name="quanity" type="number" value="1">
              </div>
              <div class="frame-39">
                <div class="frame-3a"><span class="currency" name="item_currency">Rs</span></div>
                <div class="frame-3c"><input type="number" name="item_amt" class="text-info-3d" value="0"></div>
              </div>
              <div class="frame-3e">
              <span class="total-amt">Rs</span><span class="total-amount" name="total_amt"></span>
              </div>
              <button type="button" class="cross_div"><img class="crossimg" src="assets/images/cross.svg"></button>
            </div>
          </div>

          <button class="frame-3f" id="addLineItem" type="button">
            <div class="add"></div>
            <span class="line-item">Line Item</span>
          </button>
        </div>
        <div class="frame-40">
          <div class="frame-41">
            <div class="frame-42">
              <div class="frame-43"><span class="notes">Notes</span></div>
              <div class="frame-44">
                <textarea class="notes-relevant-information" type="text" name="inv_notes"
                  placeholder="Notes- any relevant informationnot already covered"></textarea>
              </div>
            </div>
            <div class="frame-46">
              <div class="frame-47">
                <span class="terms-and-conditions">Terms and Conditions</span>
              </div>
              <div class="frame-48">
                <input class="terms-conditions-late-fees" type="text" name="inv_terms"
                  placeholder="Terms and conditions like late fees method etc">
              </div>
            </div>
          </div>
          <div class="frame-4a">
            <div class="frame-4b">
              <div class="frame-4c">
                <span class="sub-total">Sub Total</span>
              </div>
              <div class="frame-4d">
                <span class="rs">Rs.</span><span class="value-100" id="subtotal" name="subtotal"></span>
              </div>
            </div>
            <div class="shipping_tax" id="shipping_tax">
              <div class="frame-4e">
                <input type="text" value="Discount" class="frame-4f">
                <div class="frame-50">
                  <input type="number" min="0" class="date-52" id="discount" name="disc_amt"  value="0">
                </div>
              </div>
            </div>
            <div class="frame-53">
              <button class="frame-54" id="taxBtn" type="button">
                <div class="add-55"></div>
                <span class="tax">Tax</span>
              </button>
              <button class="frame-56" id="shippingBtn">
                <div class="add-57"></div>
                <span class="shipping">Shipping</span>
              </button>
            </div>
            <div class="frame-58">
              <div class="frame-59"><span class="total">Total</span></div>
              <div class="frame-5a">
                <span class="rs-5b">Rs.</span><span class="span" id="total" name="total_amt">0</span>
              </div>
            </div>
            <div class="div-frame">
              <div class="div-frame-5c">
                <span class="span-amount-paid">Amount Paid</span>
              </div>
              <div class="div-frame-5d">
                <input type="number" class="span-date" id="amountPaid" name="amt_paid" value="0" min="0">
              </div>
            </div>
            <div class="div-frame-5f">
              <div class="div-frame-60">
                <span class="span-balance-due">Balance Due</span>
              </div>
              <div class="div-frame-61">
                <span class="span-rs">Rs.</span><span class="span-62" id="balanceDue"name="balancedue"></span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="div-frame-63">
        <button class="button-frame" onclick="createInvoice(<?php echo $userId ?>)">
          <div class="div-download">
            <div class="div-vector"></div>
            <div class="div-vector-64"></div>
          </div>
          <span class="span-save-invoice" >Create
            Invoice</span>
        </button>
        <div class="div-frame-65">
          <div class="div-frame-66">
            <div class="div-line"></div>
          </div>
          <div class="div-frame-67">
            <div class="div-frame-68">
              <span class="span-currency">Currency</span>
            </div>
            <div class="div-frame-69">
              <select class="form-select" id="currency" name="currency">
                <option value="">currency</option>
                <option value="AFN">AFN - Afghan Afghani - ؋</option>
                <option value="ALL">ALL - Albanian Lek - Lek</option>
                <option value="DZD">DZD - Algerian Dinar - دج</option>
                <option value="AOA">AOA - Angolan Kwanza - Kz</option>
                <option value="ARS">ARS - Argentine Peso - $</option>
                <option value="AMD">AMD - Armenian Dram - ֏</option>
                <option value="AWG">AWG - Aruban Florin - ƒ</option>
                <option value="AUD">AUD - Australian Dollar - $</option>
                <option value="AZN">AZN - Azerbaijani Manat - m</option>
                <option value="BSD">BSD - Bahamian Dollar - B$</option>
                <option value="BHD">BHD - Bahraini Dinar - .د.ب</option>
                <option value="BDT">BDT - Bangladeshi Taka - ৳</option>
                <option value="BBD">BBD - Barbadian Dollar - Bds$</option>
                <option value="BYR">BYR - Belarusian Ruble - Br</option>
                <option value="BEF">BEF - Belgian Franc - fr</option>
                <option value="BZD">BZD - Belize Dollar - $</option>
                <option value="BMD">BMD - Bermudan Dollar - $</option>
                <option value="BTN">BTN - Bhutanese Ngultrum - Nu.</option>
                <option value="BTC">BTC - Bitcoin - ฿</option>
                <option value="BOB">BOB - Bolivian Boliviano - Bs.</option>
                <option value="BAM">BAM - Bosnia-Herzegovina Convertible Mark - KM</option>
                <option value="BWP">BWP - Botswanan Pula - P</option>
                <option value="BRL">BRL - Brazilian Real - R$</option>
                <option value="GBP">GBP - British Pound Sterling - £</option>
                <option value="BND">BND - Brunei Dollar - B$</option>
                <option value="BGN">BGN - Bulgarian Lev - Лв.</option>
                <option value="BIF">BIF - Burundian Franc - FBu</option>
                <option value="KHR">KHR - Cambodian Riel - KHR</option>
                <option value="CAD">CAD - Canadian Dollar - $</option>
                <option value="CVE">CVE - Cape Verdean Escudo - $</option>
                <option value="KYD">KYD - Cayman Islands Dollar - $</option>
                <option value="XOF">XOF - CFA Franc BCEAO - CFA</option>
                <option value="XAF">XAF - CFA Franc BEAC - FCFA</option>
                <option value="XPF">XPF - CFP Franc - ₣</option>
                <option value="CLP">CLP - Chilean Peso - $</option>
                <option value="CNY">CNY - Chinese Yuan - ¥</option>
                <option value="COP">COP - Colombian Peso - $</option>
                <option value="KMF">KMF - Comorian Franc - CF</option>
                <option value="CDF">CDF - Congolese Franc - FC</option>
                <option value="CRC">CRC - Costa Rican ColÃ³n - ₡</option>
                <option value="HRK">HRK - Croatian Kuna - kn</option>
                <option value="CUC">CUC - Cuban Convertible Peso - $, CUC</option>
                <option value="CZK">CZK - Czech Republic Koruna - Kč</option>
                <option value="DKK">DKK - Danish Krone - Kr.</option>
                <option value="DJF">DJF - Djiboutian Franc - Fdj</option>
                <option value="DOP">DOP - Dominican Peso - $</option>
                <option value="XCD">XCD - East Caribbean Dollar - $</option>
                <option value="EGP">EGP - Egyptian Pound - ج.م</option>
                <option value="ERN">ERN - Eritrean Nakfa - Nfk</option>
                <option value="EEK">EEK - Estonian Kroon - kr</option>
                <option value="ETB">ETB - Ethiopian Birr - Nkf</option>
                <option value="EUR">EUR - Euro - €</option>
                <option value="FKP">FKP - Falkland Islands Pound - £</option>
                <option value="FJD">FJD - Fijian Dollar - FJ$</option>
                <option value="GMD">GMD - Gambian Dalasi - D</option>
                <option value="GEL">GEL - Georgian Lari - ლ</option>
                <option value="DEM">DEM - German Mark - DM</option>
                <option value="GHS">GHS - Ghanaian Cedi - GH₵</option>
                <option value="GIP">GIP - Gibraltar Pound - £</option>
                <option value="GRD">GRD - Greek Drachma - ₯, Δρχ, Δρ</option>
                <option value="GTQ">GTQ - Guatemalan Quetzal - Q</option>
                <option value="GNF">GNF - Guinean Franc - FG</option>
                <option value="GYD">GYD - Guyanaese Dollar - $</option>
                <option value="HTG">HTG - Haitian Gourde - G</option>
                <option value="HNL">HNL - Honduran Lempira - L</option>
                <option value="HKD">HKD - Hong Kong Dollar - $</option>
                <option value="HUF">HUF - Hungarian Forint - Ft</option>
                <option value="ISK">ISK - Icelandic KrÃ³na - kr</option>
                <option value="INR">INR - Indian Rupee - ₹</option>
                <option value="IDR">IDR - Indonesian Rupiah - Rp</option>
                <option value="IRR">IRR - Iranian Rial - ﷼</option>
                <option value="IQD">IQD - Iraqi Dinar - د.ع</option>
                <option value="ILS">ILS - Israeli New Sheqel - ₪</option>
                <option value="ITL">ITL - Italian Lira - L,£</option>
                <option value="JMD">JMD - Jamaican Dollar - J$</option>
                <option value="JPY">JPY - Japanese Yen - ¥</option>
                <option value="JOD">JOD - Jordanian Dinar - ا.د</option>
                <option value="KZT">KZT - Kazakhstani Tenge - лв</option>
                <option value="KES">KES - Kenyan Shilling - KSh</option>
                <option value="KWD">KWD - Kuwaiti Dinar - ك.د</option>
                <option value="KGS">KGS - Kyrgystani Som - лв</option>
                <option value="LAK">LAK - Laotian Kip - ₭</option>
                <option value="LVL">LVL - Latvian Lats - Ls</option>
                <option value="LBP">LBP - Lebanese Pound - £</option>
                <option value="LSL">LSL - Lesotho Loti - L</option>
                <option value="LRD">LRD - Liberian Dollar - $</option>
                <option value="LYD">LYD - Libyan Dinar - د.ل</option>
                <option value="LTL">LTL - Lithuanian Litas - Lt</option>
                <option value="MOP">MOP - Macanese Pataca - $</option>
                <option value="MKD">MKD - Macedonian Denar - ден</option>
                <option value="MGA">MGA - Malagasy Ariary - Ar</option>
                <option value="MWK">MWK - Malawian Kwacha - MK</option>
                <option value="MYR">MYR - Malaysian Ringgit - RM</option>
                <option value="MVR">MVR - Maldivian Rufiyaa - Rf</option>
                <option value="MRO">MRO - Mauritanian Ouguiya - MRU</option>
                <option value="MUR">MUR - Mauritian Rupee - ₨</option>
                <option value="MXN">MXN - Mexican Peso - $</option>
                <option value="MDL">MDL - Moldovan Leu - L</option>
                <option value="MNT">MNT - Mongolian Tugrik - ₮</option>
                <option value="MAD">MAD - Moroccan Dirham - MAD</option>
                <option value="MZM">MZM - Mozambican Metical - MT</option>
                <option value="MMK">MMK - Myanmar Kyat - K</option>
                <option value="NAD">NAD - Namibian Dollar - $</option>
                <option value="NPR">NPR - Nepalese Rupee - ₨</option>
                <option value="ANG">ANG - Netherlands Antillean Guilder - ƒ</option>
                <option value="TWD">TWD - New Taiwan Dollar - $</option>
                <option value="NZD">NZD - New Zealand Dollar - $</option>
                <option value="NIO">NIO - Nicaraguan CÃ³rdoba - C$</option>
                <option value="NGN">NGN - Nigerian Naira - ₦</option>
                <option value="KPW">KPW - North Korean Won - ₩</option>
                <option value="NOK">NOK - Norwegian Krone - kr</option>
                <option value="OMR">OMR - Omani Rial - .ع.ر</option>
                <option value="PKR">PKR - Pakistani Rupee - ₨</option>
                <option value="PAB">PAB - Panamanian Balboa - B/.</option>
                <option value="PGK">PGK - Papua New Guinean Kina - K</option>
                <option value="PYG">PYG - Paraguayan Guarani - ₲</option>
                <option value="PEN">PEN - Peruvian Nuevo Sol - S/.</option>
                <option value="PHP">PHP - Philippine Peso - ₱</option>
                <option value="PLN">PLN - Polish Zloty - zł</option>
                <option value="QAR">QAR - Qatari Rial - ق.ر</option>
                <option value="RON">RON - Romanian Leu - lei</option>
                <option value="RUB">RUB - Russian Ruble - ₽</option>
                <option value="RWF">RWF - Rwandan Franc - FRw</option>
                <option value="SVC">SVC - Salvadoran ColÃ³n - ₡</option>
                <option value="WST">WST - Samoan Tala - SAT</option>
                <option value="SAR">SAR - Saudi Riyal - ﷼</option>
                <option value="RSD">RSD - Serbian Dinar - din</option>
                <option value="SCR">SCR - Seychellois Rupee - SRe</option>
                <option value="SLL">SLL - Sierra Leonean Leone - Le</option>
                <option value="SGD">SGD - Singapore Dollar - $</option>
                <option value="SKK">SKK - Slovak Koruna - Sk</option>
                <option value="SBD">SBD - Solomon Islands Dollar - Si$</option>
                <option value="SOS">SOS - Somali Shilling - Sh.so.</option>
                <option value="ZAR">ZAR - South African Rand - R</option>
                <option value="KRW">KRW - South Korean Won - ₩</option>
                <option value="XDR">XDR - Special Drawing Rights - SDR</option>
                <option value="LKR">LKR - Sri Lankan Rupee - Rs</option>
                <option value="SHP">SHP - St. Helena Pound - £</option>
                <option value="SDG">SDG - Sudanese Pound - .س.ج</option>
                <option value="SRD">SRD - Surinamese Dollar - $</option>
                <option value="SZL">SZL - Swazi Lilangeni - E</option>
                <option value="SEK">SEK - Swedish Krona - kr</option>
                <option value="CHF">CHF - Swiss Franc - CHf</option>
                <option value="SYP">SYP - Syrian Pound - LS</option>
                <option value="STD">STD - São Tomé and Príncipe Dobra - Db</option>
                <option value="TJS">TJS - Tajikistani Somoni - SM</option>
                <option value="TZS">TZS - Tanzanian Shilling - TSh</option>
                <option value="THB">THB - Thai Baht - ฿</option>
                <option value="TOP">TOP - Tongan pa'anga - $</option>
                <option value="TTD">TTD - Trinidad & Tobago Dollar - $</option>
                <option value="TND">TND - Tunisian Dinar - ت.د</option>
                <option value="TRY">TRY - Turkish Lira - ₺</option>
                <option value="TMT">TMT - Turkmenistani Manat - T</option>
                <option value="UGX">UGX - Ugandan Shilling - USh</option>
                <option value="UAH">UAH - Ukrainian Hryvnia - ₴</option>
                <option value="AED">AED - United Arab Emirates Dirham - إ.د</option>
                <option value="UYU">UYU - Uruguayan Peso - $</option>
                <option value="USD">USD - US Dollar - $</option>
                <option value="UZS">UZS - Uzbekistan Som - лв</option>
                <option value="VUV">VUV - Vanuatu Vatu - VT</option>
                <option value="VEF">VEF - Venezuelan BolÃ­var - Bs</option>
                <option value="VND">VND - Vietnamese Dong - ₫</option>
                <option value="YER">YER - Yemeni Rial - ﷼</option>
                <option value="ZMK">ZMK - Zambian Kwacha - ZK</option>
              </select>

            </div>
          </div>
          <button class="div-frame-6b" id="saveDefault">
            <span class="span-save-default">Save Default</span>
          </button>
          <button class="div-frame-6b" id="clearBtn">
            <span class="span-save-default">Clear All</span>
          </button>
          <div class="div-frame-6c">
            <div class="div-line-6d"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="../myjs/invoicing.js"></script>
  <script src="../myjs/eventhandling.js"></script>
  <script src="../myjs/invoice_creation.js"></script>
</body>

</html>