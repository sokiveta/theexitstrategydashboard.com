<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<?php
$currentuservals = $wpdb->get_results("SELECT * FROM `valu_cols` WHERE user_id=".$user_id." AND date != '0000-00-00' ORDER BY date");
$compvaluesql = "SELECT
(SELECT estimated_value FROM `valu_cols` WHERE user_id = ".$user_id ." AND date != '0000-00-00' ORDER BY date LIMIT 1) as 'first',
(SELECT date FROM `valu_cols` WHERE user_id = ".$user_id ." AND date != '0000-00-00' ORDER BY date LIMIT 1) as 'firstdate',
(SELECT estimated_value FROM `valu_cols` WHERE user_id = ".$user_id ." AND date != '0000-00-00' ORDER BY date DESC LIMIT 1) as 'last',
(SELECT date FROM `valu_cols` WHERE user_id = ".$user_id ." AND date != '0000-00-00' ORDER BY date DESC LIMIT 1) as 'lastdate',
(SELECT difference FROM `valu_cols` WHERE user_id = ".$user_id ." AND date != '0000-00-00' ORDER BY date DESC LIMIT 1) as 'difference'
";
$companyvals = $wpdb->get_results($compvaluesql);
foreach ( $companyvals as $companyval ) {
  $first = $companyval->first;
  $beginning_last_updated = $companyval->firstdate;
  $last = $companyval->last;
  $latest_last_updated = $companyval->lastdate;
  $difference = $companyval->difference;
}
?>
<script>

// const vw = Math.max(document.documentElement.clientWidth || 0, window.innerWidth || 0);
// console.log(vw);
// let valueCTX = document.getElementById('valueChart300');
// if (vw <= 425) {
// console.log("1: "+vw);
// 	valueCTX = document.getElementById('valueChart900');
// } else if (vw > 425 && vw <= 768) {
// console.log("2: "+vw);
// 	valueCTX = document.getElementById('valueChart600');
// }

const valueCTX = document.getElementById('valueChart');

new Chart(valueCTX, {
  type: 'line',
  data: {
		labels: [ <?php
			foreach ( $currentuservals as $currentuserval ){
				echo "'".date("M j, Y",strtotime($currentuserval->date))."',";
			}
			?> ],
		datasets: [
		{
			label: 'COMPANY VALUE',
			options: {
    		responsive: true,
				maintainAspectRatio: false,
			},
			data: [ <?php
			foreach ( $currentuservals as $currentuserval ){
				echo "'".$currentuserval->estimated_value."',";
			}
			?> ],
			fill: false,
			borderColor: '#108b3b',
			tension: 0.3
		},
		{
			label: 'EBITDA',
			data: [ <?php
			foreach ( $currentuservals as $currentuserval ){
				echo "'".$currentuserval->ebitda."',";
			}
			?> ],
			fill: false,
			borderColor: '#519cd5',
			tension: 0.3
		}
		]
	}
});

const barCTX = document.getElementById('barGraph');
new Chart(barCTX, {
  type: 'bar',
  data: {
    labels: [
			'Beginning Value (<?=date('M j, Y', strtotime($beginning_last_updated))?>)',
			'Latest Value (<?=date('M j, Y', strtotime($latest_last_updated))?>)'
		],
    datasets: [{
      label: '',
      data: [
				<?=$first?>,
				<?=$last?>
			],
			backgroundColor: [ '#72c585' ],
			borderColor: [ '#3f9a53' ],
      borderWidth: 2
    }]
  },
  options: {
		plugins: {
			legend: {
				display: false
			}
		},
    scales: {
      y: {
        beginAtZero: true,
				ticks: {
          // Include a dollar sign in the ticks
          callback: function(value, index, ticks) {
            return '$' + value;
          }
        }
      }
    }
  }
});
</script>