# CS

[Cobalt Strike是一款用于模拟红队攻击的软件，其目标是致力于「缩小渗透测试工具和高级威胁恶意软件之间的差距」](https://www.sentinelone.com/cybersecurity-101/what-is-cobalt-strike/)[1](https://bing.com/search?q=cobalt+strike介绍)[2](https://www.sentinelone.com/cybersecurity-101/what-is-cobalt-strike/)[。Cobalt Strike (CS)的创始人是Raphael Mudge，他之前在2010年的时候就发布了一款MSF图形化工具Armitage](https://www.freebuf.com/articles/network/290134.html)[3](https://www.freebuf.com/articles/network/290134.html)。

[Cobalt Strike 是一款GUI的框架式渗透工具，集成了端口转发、服务扫描，自动化溢出，多模式端口监听，win exe木马生成，win dll木马生成，java木马生成，office宏病毒生成，木马捆绑；钓鱼攻击包括：站点克隆，目标信息获取，java执行，浏览器自动攻击等等](https://www.freebuf.com/articles/network/290134.html)[1](https://bing.com/search?q=cobalt+strike介绍)[3](https://www.freebuf.com/articles/network/290134.html)。

[Cobalt Strike主要由两个部分组成：Team Server和客户端](https://zhuanlan.zhihu.com/p/93718885)[4](https://zhuanlan.zhihu.com/p/93718885)[5](https://cloud.tencent.com/developer/article/1595092)。Team Server是配置和启动Listener的地方。Listener是攻击者在C2上运行的服务，可以监听Beacon的请求 (check in)。Beacon是植入到受感染系统中的恶意程序，可以请求C2服务器并在受感染系统中执行命令。客户端是连接到Team Server并控制Beacon的地方。

[Cobalt Strike可以与Metasploit框架进行交互，并使用Metasploit提供的各种模块和功能来进行渗透测试或攻击模拟](https://zhuanlan.zhihu.com/p/93718885)[4](https://zhuanlan.zhihu.com/p/93718885)[5](https://cloud.tencent.com/developer/article/1595092)。