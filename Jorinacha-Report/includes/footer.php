<footer id="footer">

	<div class="inner">

		<center>
			<ul class="copyright">
				<li>
					<p>Inversiones Jorinacha © 2021 By Andres Salcedo | Departamento de Sistema</p>
				</li>
			</ul>
		
		<h4>Slack</h4>
		<ul class="icons">
			<li><a href="https://inv-jorinacha.slack.com" target="_blank" class="icon brands alt fa-slack"><span class="label">Slack Jorinacha</span></a></li>
		</ul>
		</center>
	</div>

</footer>
<?php
$serverName = "172.16.1.39";
$connectionInfo = array("Database" => "SISTEMAS", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
$conn = sqlsrv_connect($serverName, $connectionInfo);
sqlsrv_close( $conn );

 ?>

<!-- <script src="../../assets/navidad/nieve.js"></script> -->
</body>

</html>