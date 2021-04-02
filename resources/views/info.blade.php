<style>
    .dashboard-title .links {
        text-align: center;
        margin-bottom: 2.5rem;
    }
    .dashboard-title .links > a {
        padding: 0 25px;
        font-size: 12px;
        font-weight: 600;
        letter-spacing: .1rem;
        text-decoration: none;
        text-transform: uppercase;
        color: #fff;
    }
    .dashboard-title h1 {
        font-weight: 200;
        font-size: 2.5rem;
    }
    .dashboard-title .avatar {
        background: #fff;
        border: 2px solid #fff;
        width: 70px;
        height: 70px;
    }
</style>

<div class="dashboard-title card bg-primary">
    <div class="card-body">
        <div class="text-center ">
            <img class="avatar img-circle shadow mt-1" src="{{ admin_asset('@admin/images/logo.png') }}">

            <div class="text-center mb-1">
                <h1 class="mb-3 mt-2 text-white">BotPro</h1>
                <div class="links">
                    <a href="https://www.codefec.com" target="_blank">论坛</a>
                    <a href="http://www.node.tax/" id="doc-link" target="_blank">{{ ("NodeTax") }}</a>
                    <a href="https://jq.qq.com/?_wv=1027&k=cUVyWlmn" id="demo-link" target="_blank">{{ __('QQ群') }}</a>
                    <a href="https://zhuchunshu.com" id="demo-link" target="_blank">{{ __('博客') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>