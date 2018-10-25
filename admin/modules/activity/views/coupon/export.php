
<table width="100%" border="1">
    <tr>
        <th>ID</th>
        <th>优惠券名称</th>
        <th>优惠券面值</th>
        <th>可使用时间</th>
        <th>序列号</th>
        <th>密码</th>
        <th>生成时间</th>
        <th>可使用门店</th>
    </tr>
<?PHP foreach($data as $key=>$var){?>
    <tr>
        <td><?PHP echo $var["id"];?></td>
        <td><?PHP echo $var["couponName"];?></td>
        <td><?PHP echo $var["couponPrice"];?></td>
        <td><?PHP echo $var["endTime"];?></td>
        <td><?PHP echo $var["code"];?>&nbsp;</td>
        <td><?PHP echo $var["password"];?></td>
        <td><?PHP echo $var["createTime"];?></td>
        <td><?PHP echo $var["supplier"]?($var["supplier"]['companyName']?$var["supplier"]['companyName']:$var["supplier"]['account']):"";?></td>

    </tr>
<?PHP }?>
</table>