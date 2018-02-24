{if(isset($data))}
<div class="row-fluid">
    <div data-desktop="span3" data-tablet="span6" class="span3 responsive">
        <div class="dashboard-stat blue">
            <div class="visual">
                <i class="icon-user"></i>
            </div>
            <div class="details">
                <div class="number">
                    {$data['users']}
                </div>
                <div class="desc">
                    商户数
                </div>
            </div>
        </div>
    </div>
    <div data-desktop="span3" data-tablet="span6" class="span3 responsive">
        <div class="dashboard-stat blue">
            <div class="visual">
                <i class="icon-user"></i>
            </div>
            <div class="details">
                <div class="number">{$data['user_today']}</div>
                <div class="desc">当日新增商户数</div>
            </div>
        </div>
    </div>
    <div data-desktop="span3" data-tablet="span6  fix-offset" class="span3 responsive">
        <div class="dashboard-stat purple">
            <div class="visual">
                <i class="icon-user-md"></i>
            </div>
            <div class="details">
                <div class="number">{$data['buyers']}</div>
                <div class="desc">顾客总数</div>
            </div>
        </div>
    </div>
    <div data-desktop="span3" data-tablet="span6" class="span3 responsive">
        <div class="dashboard-stat yellow">
            <div class="visual">
                <i class="icon-cny"></i>
            </div>
            <div class="details">
                <div class="number">￥{$data['asset']}</div>
                <div class="desc">平台余额</div>
            </div>
        </div>
    </div>
</div>
<div class="row-fluid">
    <div data-desktop="span3" data-tablet="span6" class="span3 responsive">
        <div class="dashboard-stat green">
            <div class="visual">
                <i class="icon-shopping-cart"></i>
            </div>
            <div class="details">
                <div class="number">
                    {$data['order_today']}
                </div>
                <div class="desc">
                    当日订单数
                </div>
            </div>
        </div>
    </div>
    <div data-desktop="span3" data-tablet="span6" class="span3 responsive">
        <div class="dashboard-stat green">
            <div class="visual">
                <i class="icon-shopping-cart"></i>
            </div>
            <div class="details">
                <div class="number">{$data['order_week']}</div>
                <div class="desc">一周订单数</div>
            </div>
        </div>
    </div>
    <div data-desktop="span3" data-tablet="span6  fix-offset" class="span3 responsive">
        <div class="dashboard-stat green">
            <div class="visual">
                <i class="icon-shopping-cart"></i>
            </div>
            <div class="details">
                <div class="number">{$data['order_month']}</div>
                <div class="desc">30天订单数</div>
            </div>
        </div>
    </div>
</div>
<div class="row-fluid">
    <div  class="span12">
        <div id="main" style="width: auto;height:500px;">

        </div>
    </div>
</div>
{/if}