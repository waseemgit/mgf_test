<?php
//day/month/year
$data = $parameters;
?> 
    Format
    <select id="controllers_format_method">
        <option value="get_html">HTML</option>
        <option value="get_xml">XML</option>
        <option value="get_csv">CSV</option>
        <option value="get_json">JSON</option>
    </select>
    <input type="button" value="CONVERT" onclick="convertFormat('<?=BASE_URL;?>','users')">
    <br>
    Salaries
    <select id="method_for_average_salary">
        <option value="get_average_salary_engineers">Engineers</option>
        <option value="get_average_salary_internal">Internal</option>
    </select>
    <input type="button" value="GET AVERAGE" onclick="getAverageSalary('<?=BASE_URL;?>','users')">
    <span id="average_salary"></span>
    <br>
    AGE
    <select id="method_for_average_age">
        <option value="get_average_age_engineers">Engineers</option>
        <option value="get_average_age_internals">Internal</option>
        <option value="get_average_age_externals">External</option>
    </select>
    <input type="button" value="GET AVERAGE" onclick="getAverageAge('<?=BASE_URL;?>','users')">
    <span id="average_age"></span>
    
    
<span class="cachemsg"><?=$data['datamsg'];?></span> <?php if(!$data['live']){?><a href="<?=BASE_URL?>/users/reset">Reset Cache</a><?php }?>
<div class="mgf">
    <br><span class="html_users_headings">Engineers</span><br>
    <?=$data['engineersHTMLTable'];?>
    <br><span class="html_users_headings">Internal</span><br>
    <?=$data['internalHTMLTable'];?>
    <br><span class="html_users_headings">External</span><br>
    <?=$data['externalHTMLTable'];?>
</div> 
<script>
$( "div.mgf" ).scrollTop( 300 );
</script>

				