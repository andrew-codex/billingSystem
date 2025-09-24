
<div id="meterHistoryModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeMeterHistory()">&times;</span>
        <h2>Recent Meter History</h2>

        <table class="history-table">
            <thead>
                <tr>
                    <th>Meter #</th>
                    <th>Transaction</th>
                    <th>Date</th>
                    <th>Changed By</th>
                </tr>
            </thead>
            <tbody id="historyBody">
          
            </tbody>
        </table>

        <div class="modal-footer">
            <a href="{{ route('meter-history.index') }}?consumer_id=" id="fullHistoryLink">
                View Full History →
            </a>
        </div>
    </div>
</div>


<script>
function openMeterHistory(consumerId) {
    fetch(`/consumers/${consumerId}/meter-history/recent`)
        .then(response => response.json())
        .then(data => {
            let rows = '';
            data.forEach(item => {
                rows += `
                    <tr>
                        <td>${item.meter_number ?? 'N/A'}</td>
                        <td>${item.transaction_type}</td>
                        <td>${item.start_date}</td>
                        <td>${item.changed_by ?? 'System'}</td>
                    </tr>
                `;
            });
            document.getElementById("historyBody").innerHTML = rows;

           
            document.getElementById("fullHistoryLink").href = `/meter-history?consumer_id=${consumerId}`;

            document.getElementById("meterHistoryModal").style.display = "block";
        });
}

function closeMeterHistory() {
    document.getElementById("meterHistoryModal").style.display = "none";
}
</script>
