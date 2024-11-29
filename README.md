# auction_system解释
## items 商品信息
存储拍卖商品的基本信息，例如名称、描述、类别、起始价格等

seller_id：指向 user_database 的 users 表中的 id，标识商品的卖家
## auctions 拍卖信息
存储与拍卖过程相关的信息，例如拍卖开始和结束时间、保留价等

item_id：指向 items 表中的 item_id，确保每个拍卖都关联到一个商品
## bids 出价信息
记录用户对拍卖的出价，包含出价的用户、出价金额、出价时间等信息

auction_id：指向 auctions 表中的 auction_id，确保每个出价都对应某个拍卖

bidder_id：指向 user_database 的 users 表中的 id，标识出价的用户
## watchlist 收藏信息
存储用户对商品的收藏信息

user_id 和 item_id：关联到 users 和 items 表，确保收藏的用户和商品都存在

UNIQUE KEY (user_id, item_id)：保证一个用户不能重复收藏同一个商品
