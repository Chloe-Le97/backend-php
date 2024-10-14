<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alko</title>
    <script src = "https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>
<body>
    <button id="listButton">List</button>
    <button id="emptyButton">Empty</button>

    <table border="1" style="width:100%">
        <thead>
            <tr>
                <th>Number</th>
                <th>Name</th>
                <th>Bottle Size</th>
                <th>Price</th>
                <th>Price GBP</th>
                <th>Order Amount</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="tableBodyAlko">
        </tbody>
        <div id="loading" style="display:none;">Loading more data...</div>
    </table>
    <script>
       

        $(document).ready(function (){
            $('#listButton').click(function(){
                $.ajax({
                    url: 'get_data.php',
                    method: 'GET',
                    dataType: 'json',
                    success: function(data){
                        const tableContent = $('#tableBodyAlko')
                        tableContent.empty()

                        $.each(data, function(index, row) {
                            const tr = `<tr>
                                <td>${row.number}</td>
                                <td>${row.name}</td>
                                <td>${row.bottlesize}</td>
                                <td>${row.price}</td>
                                <td>${row.priceGBP}</td>
                                <td class="order-amount" data-number="${row.number}">${row.orderamount}</td>
                                <td>
                                    <button class="addOrder" data-number="${row.number}">Add</button>
                                    <button class="clearOrder" data-number="${row.number}">Clear</button>
                                </td>
                            </tr>`;
                            tableContent.append(tr);
                        });
                    }
                })
            })

            $('#emptyButton').click(function(){
                $('#tableBodyAlko').empty()
            })

            $(document).on('click', '.addOrder', function() {
                var number = $(this).data('number');
                
                // Get the current order amount
                var orderAmountCell = $('.order-amount[data-number="' + number + '"]');
                var currentAmount = parseInt(orderAmountCell.text());

                $.ajax({
                    url: 'update_data.php',
                    method: 'POST',
                    data: { number: number, action: 'add' },
                    success: function(response) {
                        // Increment and update the order amount in the UI
                        // $('.order-amount[data-number="' + number + '"]').text(currentAmount + 1);

						// Improvement 
						var data = JSON.parse(response);
						
						if (data.orderamount !== undefined) {
							$('.order-amount[data-number="' + number + '"]').text(data.orderamount);
						}
                    },
                    error: function(xhr, status, error) {
                        console.error('Error updating order amount:', error);
                    }
                });
            });


            $(document).on('click', '.clearOrder', function() {
                var number = $(this).data('number');
                
                // Get the current order amount
                var orderAmountCell = $('.order-amount[data-number="' + number + '"]');

                $.ajax({
                    url: 'update_data.php',
                    method: 'POST',
                    data: { number: number, action: 'clear' },
                    success: function(response) {
                        // Increment and update the order amount in the UI
                        $('.order-amount[data-number="' + number + '"]').text('0');
                    },
                    error: function(xhr, status, error) {
                        console.error('Error updating order amount:', error);
                    }
                });
            });

            let currentPage = 1;
            let isLoading = false;

            function loadMoreData() {
            if (isLoading) return; // Avoid loading if already in progress
            isLoading = true; // Set loading to true

            document.getElementById('loading').style.display = 'block'; // Show loading indicator
            
            const tableContent = $('#tableBodyAlko');

            $.ajax({
                url: 'get_data.php?page=' + currentPage,
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    // Append new data to the container
                        $.each(data, function(index, row) {
                            const tr = `<tr>
                                <td>${row.number}</td>
                                <td>${row.name}</td>
                                <td>${row.bottlesize}</td>
                                <td>${row.price}</td>
                                <td>${row.priceGBP}</td>
                                <td class="order-amount" data-number="${row.number}">${row.orderamount}</td>
                                <td>
                                    <button class="addOrder" data-number="${row.number}">Add</button>
                                    <button class="clearOrder" data-number="${row.number}">Clear</button>
                                </td>
                            </tr>`;
                            tableContent.append(tr);
                        });
                    currentPage++; // Increment the page counter
                },
                complete: function() {
                    isLoading = false; // Reset loading state
                    document.getElementById('loading').style.display = 'none'; // Hide loading indicator
                }
            });
        }

        // Event listener for scrolling
            $(window).scroll(function() {
                // Check if the user has scrolled near the bottom of the page
                if ($(window).scrollTop() + $(window).height() >= $(document).height() - 100) {
                    loadMoreData(); // Load more data
                }
            });
        })
    </script>
</body>
</html>

