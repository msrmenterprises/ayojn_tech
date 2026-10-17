<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Receipt</title>
    <link rel="stylesheet" href="https://alfanzo.in/invoice.css">
  </head>
  <body>
  <div  >
  <div class="info-grid" style="float:left; width: 100%;">
    <h1 style="float:left;">Receipt</h1>
	<h1 style="color:gray; float:right;">Ayojn Consulting FZC
</h1>
	</div>
  <br/><br/><br/><br/>

    <div class="section" style="">
      
        <p><strong>Invoice number:</strong> {{$invoice_no}}</p>
        <p><strong>Receipt Number:</strong>{{$rec_no}}</p>
        <p><strong>Date Paid:</strong> {{$due_date}}</p>
        <p><strong>Payment Method:</strong> {{$payment_mode}}</p>
     
      
    </div>

    <div class="section info-grid">
	
	  <div style="float:left; width:50%">
        <h2>Ayojn Consulting FZC</h2>
        <p>440 N Barranca Ave<br>#4750<br>Covina, California 91723<br>United States<br/>
        +1 415–691–2009<br>support@ayojn.com</p>
      </div>
	  <div style="float:right; width:50%">
      <h2>Bill to</h2>
      <p>{{$entity}}<br>{{$phone_no}}<br>{{$email}}</p>
	  </div>

    <div class="bold" >${{$amount}} USD due {{$due_date}}</div>
    </div>

    

    <table>
      <thead>
        <tr>
          <th>Description</th>
          <th>Qty</th>
          <th>Unit price</th>
          <th>Amount</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>{{$invoice_item_title}}</td>
          <td>1</td>
          <td>${{$amount}}</td>
          <td>${{$amount}}</td>
        </tr>
       
      </tbody>
    </table>

    <table style="width:40%; float:right">
      <tr>
        <td colspan="3" class="total-label">Subtotal</td>
        <td class="total">${{$amount}}</td>
      </tr>
      <tr>
        <td colspan="3" class="total-label">Total</td>
        <td class="total">${{$amount}}</td>
      </tr>
      <tr>
        <td colspan="3" class="total-label">Amount Paid</td>
        <td class="total">${{$amount}}</td>
    </table>

    
  </div>

  </body>
</html>
