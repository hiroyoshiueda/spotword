<script src="http://widgets.twimg.com/j/2/widget.js"></script>
<script>
{literal}
new TWTR.Widget({
  version: 2,
  type: 'profile',
  rpp: 4,
  interval: 6000,
  width: 300,
  height: 300,
  theme: {
    shell: {
      background: '#f7a82a',
      color: '#ffffff'
    },
    tweets: {
      background: '#ffffff',
      color: '#424242',
      links: '#7a5100'
    }
  },
  features: {
    scrollbar: false,
    loop: false,
    live: false,
    hashtags: true,
    timestamp: true,
    avatars: false,
    behavior: 'all'
  }
}).render().setUser('{/literal}{$twitter_id}{literal}').start();
{/literal}
</script>
