USE QuanLyDuLich;

-- Dữ liệu 
-- Món Ăn
INSERT INTO MONAN
(TenMonAn, DiaChiMonAn, MapLinkMonAn, LoaiMonAn, GiaMonAn, MoTaMonAn, ImageLinkMonAn)
VALUES
('Mì Quảng',
 'Gợi ý: 135 Tiểu La, Hoà Cường Bắc, Hải Châu, Đà Nẵng',
 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3834.4004689740896!2d108.21395827604977!3d16.044695384631243!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x314219c0f89fde09%3A0xdf536a87a6562de8!2zMTM1IFRp4buDdSBMYSwgSG_DoCBDxrDhu51uZyBC4bqvYywgSOG6o2kgQ2jDonUsIMSQw6AgTuG6tW5nIDU1MDAwMCwgVmnhu4d0IE5hbQ!5e0!3m2!1svi!2s!4v1761787635665!5m2!1svi!2s',
 'dacSan',
 '35.000 - 55.000 VNĐ/tô',
 'Món đặc sản biểu tượng của Đà Nẵng với sợi mì dai mềm, nước dùng đậm đà, ăn kèm rau sống, bánh tráng nướng và đậu phộng rang.',
 'pic/Mì quảng.jpg'),

('Bún chả cá',
 'Gợi ý: 204 Phan Đăng Lưu, Hoà Cường Bắc, Cẩm Lệ, Đà Nẵng',
 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3834.5590172957345!2d108.20965857604965!3d16.036455484638303!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3142199d1b05643b%3A0x576af3f1ab1ca0f!2zQsO6biBDaOG6oyBRdeG6oXQgLSDEkOG6rW0gxJHDoCBIw6AgTuG7mWk!5e0!3m2!1svi!2s!4v1761787755919!5m2!1svi!2s',
 'dacSan',
 '45.000 - 60.000 VNĐ/tô',
 'Món đặc sản Đà Nẵng với nước dùng ngọt thanh, chả cá dai giòn, ăn kèm rau sống, hành phi và chút ớt cay, mang hương vị đậm đà khó quên.',
 'pic/Bún Chả.jpg'),

('Bún mắm nêm',
 'Gợi ý: 145 Huỳnh Thúc Kháng, Phước Ninh, Hải Châu, Đà Nẵng',
 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3834.155931529221!2d108.21335038611934!3d16.057396144965182!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x314219c96950e5c9%3A0xc62708eff1905f7!2zQsO6biBN4bqvbSBCw6AgxJDDtG5n!5e0!3m2!1svi!2s!4v1761788399376!5m2!1svi!2s',
 'dacSan',
 '30.000 - 40.000 VNĐ/phần',
 'Món ăn dân dã đặc trưng Đà Nẵng với mắm nêm thơm lừng, kết hợp thịt heo quay hoặc luộc, chả bò, rau sống và đậu phộng, tạo hương vị đậm đà khó quên.',
 'pic/Bún nêm.jpeg'),

('Gỏi cá Nam Ô',
 'Gợi ý: 130 Huỳnh Thúc Kháng, Phước Ninh, Hải Châu, Đà Nẵng',
 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3834.1328319169515!2d108.21544057604994!3d16.058595384619455!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31421b6643084119%3A0x46707966d33409f1!2zR-G7j2kgY8OhIE5hbSDDlCBBLiBTaW5o!5e0!3m2!1svi!2s!4v1761788535121!5m2!1svi!2s',
 'dacSan',
 '50.000 - 150.000 VNĐ/phần',
 'Món đặc sản độc đáo của Đà Nẵng làm từ cá trích tươi, trộn cùng rau rừng, hành phi, đậu phộng và nước chấm cay mặn, mang hương vị tươi ngon đặc trưng của biển.',
 'pic/Gỏi cá Nam ô.jpg'),

('Bánh tráng cuốn thịt heo',
 'Gợi ý: 35 Đỗ Thúc Tịnh, Khuê Trung, Cẩm Lệ, Đà Nẵng',
 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3834.7346767907434!2d108.20705197604939!3d16.027321484646095!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3142198c62eb4391%3A0x642295b8af7cab9c!2zUXXDoW4gTeG6rXU!5e0!3m2!1svi!2s!4v1761793560144!5m2!1svi!2s',
 'dacSan',
 '50.000 - 200.000 VNĐ/phần',
 'Món đặc sản với thịt heo hai đầu da thái mỏng, cuốn cùng rau sống, dưa leo và chấm mắm nêm đậm đà, kết hợp bánh tráng phơi sương và nước chấm đặc trưng.',
 'pic/Bánh tráng cuốn.jpg'),

('Bánh canh ruộng',
 'Gợi ý: 20 Hà Thị Thân, An Hải Trung, Sơn Trà, Đà Nẵng',
 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3834.1302564993084!2d108.22920157604992!3d16.05872908461929!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3142196930eab33d%3A0x49116ee973284dfc!2zQsOhbmggQ2FuaCBSdeG7mW5n!5e0!3m2!1svi!2s!4v1761793688808!5m2!1svi!2s',
 'dacSan',
 '20.000 - 35.000 VNĐ/tô',
 'Món ăn dân dã Đà Nẵng với sợi bánh canh dai, nước dùng ngọt thanh nấu từ xương heo, ăn kèm chả cá, hành phi và rau sống.',
 'pic/Bánh canh ruộng.jpg'),

('Cá nục cuốn bánh tráng Đà Nẵng',
 '59/19 Núi Thành, Bình Thuận, Hải Châu, Đà Nẵng.',
 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3834.2734850340603!2d108.21834037604994!3d16.05129188462561!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31421955716670c1%3A0xd5ffea052f4998b8!2zQ8OhIE7hu6VjIEN14buRbiBCw6FuaCBUcsOhbmcgLSBCw6kgTsOidQ!5e0!3m2!1svi!2s!4v1761793823102!5m2!1svi!2s',
 'dacSan',
 '50.000 - 120.000 VNĐ/phần',
 'Cá nục tươi, tẩm ướp sả, tỏi, hành tím, cuốn cùng rau sống và bánh tráng, chấm nước mắm cay nồng, đặc sản hấp dẫn của Đà Nẵng.',
 'pic/Cá nục.jpg'),

('Bê thui Cầu Mống',
 'Gợi ý: 138 Nguyễn Tri Phương, Thạc Gián, Thanh Khê, Đà Nẵng',
 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3834.0889602840566!2d108.20228927604997!3d16.060872784617416!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x314219822ef9cd0f%3A0x6d652e063583061b!2zQsOqIFRodWkgTcaw4budaSBD4bqndSBN4buRbmc!5e0!3m2!1svi!2s!4v1761794012652!5m2!1svi!2s',
 'dacSan',
 '150.000 - 400.000 VNĐ/phần',
 'Thịt bê non thui vàng, thái mỏng, chấm mắm cái hoặc mắm nêm, ăn kèm rau sống và bánh tráng, mang vị ngọt và hương thơm đặc trưng của Đà Nẵng.',
 'pic/Bê.webp'),

('Bún thịt nướng',
 'Gợi ý: 191 Trần Phú, Phước Ninh, Hải Châu, Đà Nẵng',
 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3833.9942093362943!2d108.22120507605011!3d16.065790284613207!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31421832ef8895e5%3A0x2a6622b0e022cf25!2zQsO6biB0aOG7i3Qgbsaw4bubbmcgTmdh!5e0!3m2!1svi!2s!4v1761795434960!5m2!1svi!2s',
 'dacSan',
 '25.000 - 40.000 VNĐ/tô',
 'Món đặc sản Đà Nẵng với thịt heo nướng thơm lừng, ăn cùng bún tươi, rau sống, đậu phộng, nổi bật nhờ nước lèo làm từ gan và nước tương đậu nành, sệt vừa phải, tạo hương vị đậm đà khó quên.',
 'pic/Bún thịt nướng.jpg'),

('Bún bò',
 'Gợi ý: 236 Phan Đăng Lưu, Khuê Trung, Cẩm Lệ, Đà Nẵng',
 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3834.551182081834!2d108.20840447604965!3d16.03686278463801!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x314219006d2c8aa5%3A0x3e829a89b72e2cc8!2zQsO6biBiw7IgSOG6sW5nIDI!5e0!3m2!1svi!2s!4v1761795501344!5m2!1svi!2s',
 'dacSan',
 '30.000 - 50.000 VNĐ/tô',
 'Món đặc sản miền Trung với nước dùng đậm đà, thịt bò mềm, ăn kèm chả bò, rau sống, giá đỗ và chút sa tế cay, sợi bún nhỏ, có thể thêm mắm ruốc, hành tím, muối, ớt.',
 'pic/Bún bò.webp'),
 
('Hải sản Phố',
 'Số 100, đường Tiểu La, Hòa Thuận Tây, Hải Châu, Đà Nẵng',
 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3834.4037485346526!2d108.21010937604976!3d16.04452498463143!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x314219bf605e3237%3A0xc1d0ac3fda7c455d!2zSG9hIFZpw6puIFJlc3RhdXJhbnQgLSAxMDAgVGnhu4N1IExhLCDEkMOgIE7hurVuZw!5e0!3m2!1svi!2s!4v1761835967705!5m2!1svi!2s',
 'haiSan',
 '45.000 - 300.000 VNĐ/món',
 'Quán nổi tiếng với hải sản tươi ngon, giữ trọn hương vị biển Đà Nẵng.',
 'pic/Hải sản 1.jpeg'),

('Mộc Quán',
 'Số 26, Tô Hiến Thành, quận Sơn Trà, Đà Nẵng',
 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3834.0287661157954!2d108.23892657605005!3d16.06399698461461!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x314217721a8aafc7%3A0x1c948e37d3931fef!2zSOG6o2kgc-G6o24gTeG7mWMgcXXDoW4gxJDDoCBO4bq1bmc!5e0!3m2!1svi!2s!4v1761836065988!5m2!1svi!2s',
 'haiSan',
 '70.000 - 300.000 VNĐ/món',
 'Với không gian decor đồng quê, menu đa dạng từ BBQ, hải sản đến các món dân dã.',
 'pic/Hải sản 2.jpg'),

('Hải sản Kỳ Đồng',
 '1519, Nguyễn Tất Thành, quận Thanh Khê, Đà Nẵng',
 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15334.45782964829!2d108.1490440645654!3d16.085484422732826!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x314219a6ed262163%3A0xe3ad3b238537bbf6!2zSOG6o2kgU-G6o24gS-G7syDEkOG7k25n!5e0!3m2!1svi!2s!4v1761836323951!5m2!1svi!2s',
 'haiSan',
 '45.000 - 120.000 VNĐ/món',
 'Nổi tiếng với các món tươi ngon như cua rang me, ốc xào sả ớt, lẩu hải sản béo ngậy.',
 'pic/Hải sản 4.jpg'),

('Hải sản Năm Đảnh',
 '139/59/38, Trần Quang Khải, phường Thọ Quang, quận Sơn Trà, Đà Nẵng',
 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3833.2918115152215!2d108.25053817605074!3d16.10219858458214!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x314217e3980e8991%3A0xe10070aa2c0a3e13!2zSOG6o2kgc-G6o24gTsSDbSDEkOG6o25o!5e0!3m2!1svi!2s!4v1761836418390!5m2!1svi!2s',
 'haiSan',
 '60.000 - 100.000 VNĐ/món',
 'Nổi bật với hải sản tươi, giá cả hợp lý, thu hút đông khách địa phương.',
 'pic/Hải sản 3.jpg'),
 
 ('Ốc hút',
 'Gợi ý: 277 Đống Đa, Thạch Thang, Hải Châu, Đà Nẵng',
 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3833.851712811612!2d108.21137077605022!3d16.07318298460685!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3142192d9bf7fa63%3A0x3bf5b72d072fc5d0!2z4buQYyBIw7p0IDI3Nw!5e0!3m2!1svi!2s!4v1761796401922!5m2!1svi!2s',
 'duongPho',
 '20.000 - 50.000 VNĐ/phần',
 'Món đặc sản Đà Nẵng với ốc xào sả, ớt, lá chanh, đôi khi thêm nước dừa, ăn kèm xoài hoặc đu đủ bào, hành phi và bánh tráng nướng.',
 'pic/Ốc hút.jpg'),

('Mít trộn',
 'Gợi ý: 362 Hoàng Diệu, Bình Thuận, Hải Châu, Đà Nẵng',
 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3834.176011838286!2d108.21444167604982!3d16.05635358462129!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x314219d8d5ba8caf%3A0xf36a74bc6699fc35!2zRMOsIExhbiAoIE3DrXQgQ2h14buRaSBUcuG7mW4gKQ!5e0!3m2!1svi!2s!4v1761796534309!5m2!1svi!2s',
 'duongPho',
 '15.000 - 30.000 VNĐ/phần',
 'Món ăn vặt với mít non trộn da heo, đậu phộng, rau răm, nước mắm cay, đôi khi thêm bò khô và hành phi, dùng kèm bánh đa.',
 'pic/Mít trộn.jpg'),

('Bánh bèo',
 'Gợi ý: 409 Núi Thành, Hoà Cường Bắc, Hải Châu, Đà Nẵng',
 '',
 'duongPho',
 '20.000 - 35.000 VNĐ/phần',
 'Món ăn vặt dân dã với bánh đổ chén nhỏ, ăn kèm tôm chưng, bánh mì chiên, đậu phộng và nước mắm.',
 'pic/Bánh bèo.jpg'),

('Bánh tráng kẹp',
 'Gợi ý: 250 254 Đ. Nguyễn Hoàng, Phước Ninh, Thanh Khê, Đà Nẵng',
 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3834.131795588356!2d108.2106012760499!3d16.058649184619274!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x314219b60f698737%3A0xc846db29e7c776c3!2zQsOhbmggVHLDoW5nIEvhurlwIETDrCBFbQ!5e0!3m2!1svi!2s!4v1761796636316!5m2!1svi!2s',
 'duongPho',
 '10.000 - 20.000 VNĐ/cái',
 'Món ăn vặt với bánh tráng nướng giòn, nhân trứng cút, pate, hành phi, chấm tương ớt hoặc mắm ruốc.',
 'pic/Bánh tráng kẹp.jpg'),

('Bánh mì bột lọc',
 'Gợi ý: 104 Đ. Lê Độ, Chính Giám, Thanh Khê, Đà Nẵng',
 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3833.95388108067!2d108.19654548612048!3d16.067882844933155!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x314218529000fa17%3A0x279266550500ee56!2zMTA0IMSQLiBMw6ogxJDhu5ksIENow61uaCBHacOhbSwgVGhhbmggS2jDqiwgxJDDoCBO4bq1bmcgNTUwMDAwLCBWaeG7h3QgTmFt!5e0!3m2!1svi!2s!4v1761796716000!5m2!1svi!2s',
 'duongPho',
 '10.000 - 20.000 VNĐ/cái',
 'Món ăn vặt với nhân bột lọc dai mềm, tôm, thịt, gói trong bánh mì giòn thơm, phổ biến ở các xe đẩy rong.',
 'pic/Bánh mì bột lộc.webp'),

('Chè sầu riêng',
 'Gợi ý: 189 Hoàng Diệu, Phước Ninh, Hải Châu, Đà Nẵng',
 '',
 'duongPho',
 '30.000 - 40.000 VNĐ/ly',
 'Món ăn vặt ngọt ngào với sầu riêng tươi, cốt dừa, đậu xanh và các loại topping, mang hương vị đặc trưng Đà Nẵng.',
 'pic/Chè sầu riêng.webp'),

('Kem bơ',
 'Gợi ý: 98 Nguyễn Văn Thoại, Bắc Mỹ Phú, Ngũ Hành Sơn, Đà Nẵng',
 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3834.21018680488!2d108.23829887604988!3d16.05457908462275!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3142177c4d0ca37d%3A0x7891f631e33bb89c!2zS2VtIELGoSBOZ8OibiBIw6A!5e0!3m2!1svi!2s!4v1761796775728!5m2!1svi!2s',
 'duongPho',
 '25.000 - 30.000 VNĐ/ly',
 'Món tráng miệng mát lạnh với bơ dầm mịn, kem vani và topping dừa khô, vị béo ngậy, ngọt nhẹ.',
 'pic/Kem bơ.webp'),

('Chè xoa xoa hạt lựu',
 'Gợi ý: 46 Trần Bình Trọng, Hải Châu, Đà Nẵng',
 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3833.990892782476!2d108.21587677605011!3d16.065962384613044!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31421948f4043b27%3A0xdbfa63b96cbbaa99!2zQ2jDqCBYb2EgWG9hIEjhuqF0IEzhu7F1IFRy4bqnbiBCw6xuaCBUcuG7jW5n!5e0!3m2!1svi!2s!4v1761796813486!5m2!1svi!2s',
 'duongPho',
 '15.000 - 25.000 VNĐ/ly',
 'Món chè mát lành với xoa xoa, hạt lựu, thạch đen, nước cốt dừa, đậu xanh đánh và nước đường.',
 'pic/Chè xoa xoa.jpg'),
 
('Rong biển Mỹ Khê',
 'Gợi ý: 204 Phan Đăng Lưu, Hoà Cường Bắc, Cẩm Lệ, Đà Nẵng',
 '',
 'dacSanQua',
 '400.000 - 500.000 VNĐ/kg',
 'Rong biển từ vùng biển sạch, giàu dinh dưỡng, dùng chế biến canh, xào, gỏi, salad, chè, thạch. Vị ngọt thanh, dai giòn, tốt cho sức khỏe.',
 'pic/Rong biển.webp'),

('Tré',
 '',
 '',
 'dacSanQua',
 '80.000 - 100.000 VNĐ/hũ 500g',
 'Món đặc sản làm từ thịt heo, tai heo, ủ chua tự nhiên, dùng làm gỏi, ăn vặt hoặc mồi nhậu. Dễ bảo quản và mang đi xa.',
 'pic/Tré.jpg'),

('Nước mắm Nam Ô',
 '',
 '',
 'dacSanQua',
 '100.000 - 200.000 VNĐ/lít',
 'Đặc sản truyền thống làm từ cá cơm tươi, ủ theo phương pháp cổ truyền, mang vị mặn mà, thơm nồng, thích hợp làm quà.',
 'pic/Mắm.jpeg'),

('Chả bò Đà Nẵng',
 '',
 '',
 'dacSanQua',
 '350.000 - 500.000 VNĐ/kg',
 'Chả bò làm từ thịt bò tươi 100%, nêm gia vị đặc trưng, dai giòn, béo thơm, thích hợp làm quà tặng.',
 'pic/Chả bò.jpg'),

('Trà sâm dứa Đà Nẵng',
 '',
 '',
 'dacSanQua',
 '100.000 - 150.000 VNĐ/hộp 100g',
 'Trà sâm dứa thơm mát, hòa quyện giữa lá trà non sấy khô và lá dứa tươi, thích hợp làm quà tặng và tốt cho sức khỏe.',
 'pic/Sâm.jpg'),

('Mực rim me Đà Nẵng',
 '',
 '',
 'dacSanQua',
 '200.000 - 300.000 VNĐ/500g',
 'Mực sữa tươi rim me, đường và ớt, vị chua ngọt, cay mặn, thích hợp làm quà và rất đặc trưng phố biển.',
 'pic/mực.webp'),

('Bò khô Đà Nẵng',
 '',
 '',
 'dacSanQua',
 '500.000 - 700.000 VNĐ/kg',
 'Bò khô tẩm ướp gia vị gừng, nghệ, ớt, tỏi, dai mềm, thơm lừng, thích hợp làm quà và dễ vận chuyển.',
 'pic/Khô bò.webp'),

('Bánh khô mè Đà Nẵng',
 '',
 '',
 'dacSanQua',
 '50.000 - 80.000 VNĐ/hộp 500g',
 'Bánh giòn tan, ngọt thanh, thơm lừng, làm từ bột mì và mè rang, đặc sản truyền thống nổi tiếng Đà Nẵng.',
 'pic/Khô mè.webp'),
 
 ('Chợ Cồn – Nơi mua bán tấp nập của người dân bản địa',
 '90 Hùng Vương, Hải Châu, Đà Nẵng',
 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3833.939316979617!2d108.2176771760501!3d16.068638484610776!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3142183472c301a7%3A0x4ca1dfabf314ed00!2zOTAgSMO5bmcgVsawxqFuZywgSOG6o2kgQ2jDonUsIMSQw6AgTuG6tW5nIDU1MDAwMCwgVmnhu4d0IE5hbQ!5e0!3m2!1svi!2s!4v1761804064311!5m2!1svi!2s',
 'thuongthuc',
 '',
 'Chợ Cồn là khu chợ nổi tiếng lâu đời của Đà Nẵng, được xem như ''trái tim mua sắm'' của thành phố. Ban đầu chỉ là khu chợ nhỏ, nay Chợ Cồn đã phát triển thành trung tâm giao thương sầm uất với hơn 2000 gian hàng. Ngoài mua sắm, nơi đây còn được mệnh danh là ''thiên đường ẩm thực Đà Nẵng'' với vô vàn món đặc sản như bánh xèo, bún mắm, mì Quảng, gỏi cuốn, bánh bèo, bánh tráng kẹp hay chè sầu riêng.',
 'pic/Chợ cồn.jpg'),

('Chợ hải sản – Trải nghiệm độc đáo vào buổi bình minh',
 'Yên Khê 2, Thanh Khê Đông, Thanh Khê, Đà Nẵng',
 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3833.845585689778!2d108.17701637605016!3d16.073500784606637!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x314218fc9229be03%3A0x1ad83cdec99b4e18!2zWcOqbiBLaMOqIDIsIFRoYW5oIEtow6osIMSQw6AgTuG6tW5nLCBWaeG7h3QgTmFt!5e0!3m2!1svi!2s!4v1761804180319!5m2!1svi!2s',
 'thuongthuc',
 '',
 'Chợ hải sản Đà Nẵng nằm trên đường Hoàng Sa, dọc tuyến đường dẫn lên chùa Linh Ứng, mang không gian mở và mộc mạc. Phiên chợ bắt đầu từ 3 giờ sáng, nơi ngư dân mang về những mẻ hải sản tươi rói. Đây là nơi lý tưởng để cảm nhận nét đời thường giản dị mà đầy sức sống của Đà Thành, đồng thời mua về hải sản tươi ngon nhất trong ngày.',
 'pic/Chợ Cá.jpg'),

('Chợ đêm Helio – Thiên đường ẩm thực về đêm ở Đà Nẵng',
 'Đường 2/9, quận Hải Châu, Đà Nẵng',
 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3834.4128730021143!2d108.2204946760497!3d16.04405088463181!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x314219cf864109a1%3A0xae486547f322b52b!2zxJAuIDIgVGjDoW5nIDksIEjhuqNpIENow6J1LCDEkMOgIE7hurVuZywgVmnhu4d0IE5hbQ!5e0!3m2!1svi!2s!4v1761804242748!5m2!1svi!2s',
 'thuongthuc',
 '',
 'Chợ đêm Helio là khu chợ đêm sầm uất và nhộn nhịp bậc nhất Đà Nẵng, nổi tiếng với hơn 150 gian hàng ẩm thực đa dạng. Ngoài ra còn có âm nhạc, lễ hội, không gian rộng rãi và không khí sôi động, là điểm đến lý tưởng để vui chơi và thưởng thức ẩm thực Đà Nẵng về đêm.',
 'pic/Chợ đêm Helio.jpg'),

('Chợ Hàn – Thiên đường của đồ lưu niệm và mua sắm',
 '119 Trần Phú, Hải Châu, Đà Nẵng',
 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3833.94784767546!2d108.22164677605012!3d16.068195884611157!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x314218323e56c4ef%3A0x79ca6f8db5faa264!2zMTE5IFRy4bqnbiBQaMO6LCBI4bqjaSBDaMOidSwgxJDDoCBO4bq1bmcgNTUwMDAwLCBWaeG7h3QgTmFt!5e0!3m2!1svi!2s!4v1761804286760!5m2!1svi!2s',
 'thuongthuc',
 '',
 'Chợ Hàn là khu chợ lâu đời nổi tiếng nhất Đà Nẵng, nơi bày bán đa dạng hàng hóa từ hải sản khô, mắm, tương ớt, tỏi Lý Sơn đến hoa quả nhiệt đới. Ngoài ra còn có dịch vụ may đo tại chỗ, mang đến trải nghiệm mua sắm đậm chất Đà Thành – thân thiện và đầy sắc màu.',
 'pic/Chợ Hàn.jpg'),

('Chợ Bắc Mỹ An – Thiên đường ẩm thực và mua sắm địa phương',
 '25 Nguyễn Bá Lân, Phường Bắc Mỹ An, Quận Ngũ Hành Sơn, Đà Nẵng',
 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3834.4488650318062!2d108.23943687604961!3d16.042180634633322!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31421760dcd59b65%3A0xa5148e15ff526fe0!2zQ2jhu6MgQuG6r2MgTeG7uSBBbg!5e0!3m2!1svi!2s!4v1761804349027!5m2!1svi!2s',
 'thuongthuc',
 '',
 'Chợ Bắc Mỹ An là điểm đến quen thuộc của người dân địa phương, nổi bật với các món ăn đường phố đa dạng và sinh tố hoa quả tươi ngon. Không gian chợ thoáng đãng, quầy hàng gọn gàng, vừa mua sắm vừa thưởng thức hương vị đặc trưng của ẩm thực Đà Nẵng.',
 'pic/Bắc mỹ á.jpg');




INSERT INTO DIADANH
  (TenDD, DiaChiDD, MapLinkDD, MoTaDD, ImageDD, LoaiDD)
VALUES
('Bãi Biển Mỹ Khê','Võ Nguyên Giáp, Phước Mỹ, Sơn Trà, Đà Nẵng',
 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d7510.326805541372!2d108.23757912776612!3d16.06199747013214!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31421782f7fa0ee3%3A0xeafb8ba272ee55ac!2zQsOjaSBiaeG7g24gTeG7uSBLaMOq!5e0!3m2!1svi!2s!4v1761386143516!5m2!1svi!2s',
 'Bãi biển nổi tiếng với cát trắng mịn, nước trong xanh; điểm ngắm bình minh/hoàng hôn lý tưởng.',
 'anh/mykhe.jpg','thiennhien'),


('Núi Ngũ Hành Sơn','81 Huyền Trân Công Chúa, Hòa Hải, Ngũ Hành Sơn, Đà Nẵng',
 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3835.188299429895!2d108.26058557604907!3d16.00371038466636!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31420dd4e14b2edb%3A0xbc6e1faf738be4c5!2zTmfFqSBIw6BuaCBTxqFu!5e0!3m2!1svi!2s!4v1761386646650!5m2!1svi!2s',
 'Quần thể núi đá vôi – hang động – chùa chiền đặc sắc; di tích lịch sử – văn hóa cấp Quốc gia.',
 'anh/nguhanhson.jpg','thiennhien'),


('Bán Đảo Sơn Trà','Bán đảo Sơn Trà, Thọ Quang, Sơn Trà, Đà Nẵng',
 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d122650.24616582367!2d108.11980388469398!3d16.12659971116778!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31423d72d1be522d%3A0x1e7339a6534e4e7!2zQsOhbiDEkeG6o28gU8ahbiBUcsOg!5e0!3m2!1svi!2s!4v1761810351876!5m2!1svi!2s',
 '“Lá phổi xanh” của thành phố; bãi Bụt, chùa Linh Ứng, cung đường Hoàng Sa tuyệt đẹp; có voọc chà vá.',
 'anh/Sontra.png','thiennhien'),


('Cầu Vàng','Sun World Bà Nà Hills, Hòa Phú, Hòa Vang, Đà Nẵng',
 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3835.356680734252!2d107.99398757604877!3d15.994937484673793!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3141f73d19c5d38f%3A0x63766c11470ab50d!2zQ-G6p3UgVsOgbmc!5e0!3m2!1svi!2s!4v1761386912430!5m2!1svi!2s',
 'Cây cầu “bàn tay khổng lồ” nổi tiếng; đi bộ giữa mây núi, kết hợp vườn hoa – làng Pháp – cáp treo.',
 'anh/cauvang.jpg','congtrinh'),


('Cầu Rồng','Đường Nguyễn Văn Linh – Trần Hưng Đạo, Hải Châu–Sơn Trà, Đà Nẵng',
 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3834.084502275844!2d108.22511767605005!3d16.061104184617214!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x314219d2f38ce45d%3A0xbfa47dd116d4db88!2zQ-G6p3UgUuG7k25nLCDEkMOgIE7hurVuZyA1NTAwMDAsIFZp4buHdCBOYW0!5e0!3m2!1svi!2s!4v1761383554572!5m2!1svi!2s',
 'Biểu tượng hiện đại của Đà Nẵng; cuối tuần có phun lửa, phun nước.',
 'anh/CauRong.png','congtrinh'),


('Cầu Tình Yêu','Trần Hưng Đạo (gần Cá chép hóa Rồng), An Hải Tây, Sơn Trà, Đà Nẵng',
 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3834.048040202771!2d108.22723967605002!3d16.062996684615584!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3142196e746d9c5f%3A0x91cfbbc68781d139!2zQ-G6p3UgVMOsbmggWcOqdSAtIExvdmUgUGllcg!5e0!3m2!1svi!2s!4v1761386959645!5m2!1svi!2s',
 'Cầu đi bộ lãng mạn với ổ khóa tình yêu, lung linh về đêm.',
 'anh/cautinhyeu.jpg','congtrinh'),


('Chùa Linh Ứng','Bãi Bụt, đường Hoàng Sa, Thọ Quang, Sơn Trà, Đà Nẵng',
 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3835.1807428214015!2d108.26174147604922!3d16.00410398466608!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3142110e6f11cc15%3A0x13a307b874043572!2zQ2jDuWEgTGluaCDhu6huZyDigJMgTmfFqSBIw6BuaCBTxqFu!5e0!3m2!1svi!2s!4v1761386845365!5m2!1svi!2s',
 'Chùa Linh Ứng Bãi Bụt với tượng Quan Âm 67m hướng biển; không gian thanh tịnh.',
 'anh/tuongba.jpg','vanhoa'),


('Đỉnh Bàn Cờ','Bán đảo Sơn Trà, Thọ Quang, Sơn Trà, Đà Nẵng',
 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15331.792384854098!2d108.26564616417757!3d16.1199856530684!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31423d71a211a64f%3A0xb65a8033e2183c23!2zxJHhu4luaCBCw6BuIEPhu50!5e0!3m2!1svi!2s!4v1761810386894!5m2!1svi!2s',
 'Điểm ngắm toàn cảnh TP ở cao độ >700m; tượng tiên ông đánh cờ.',
 'anh/Dinhbanco.png','thiennhien'),


('Bãi Bụt','Hoàng Sa, Thọ Quang, Sơn Trà, Đà Nẵng',
 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3833.3683726500362!2d108.26887377602209!3d16.09823398458546!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x314217b9ff96e6c9%3A0xe9575c07f27853c4!2zQsOjaSBC4buldA!5e0!3m2!1svi!2s!4v1761810626176!5m2!1svi!2s',
 'Bãi biển hoang sơ cạnh chùa Linh Ứng; đẹp nhất mùa khô 2–9.',
 'anh/BaiBut.jpg','thiennhien'),


('Bảo tàng Chăm','2A đường 2 Tháng 9, Bình Hiên, Hải Châu, Đà Nẵng',
 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3834.0999817377597!2d108.22069277602132!3d16.060300684617875!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x314219cdb3006a2d%3A0x62ca993f60c3a12c!2zQuG6o28gdMOgbmcgxJBpw6p1IGto4bqvYyBDaMSDbSDEkMOgIE7hurVuZw!5e0!3m2!1svi!2s!4v1761810659693!5m2!1svi!2s',
 'Lưu giữ >2.000 hiện vật văn hóa Chăm.',
 'anh/BaoTang.webp','vanhoa'),


('Đèo Hải Vân','Đèo Hải Vân (QL1A), Hòa Hiệp Bắc, Liên Chiểu, Đà Nẵng',
 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15325.589398796657!2d108.12303326419922!3d16.199999501975853!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31422151a4984555%3A0x9a66217b26b3f759!2zxJDDqG8gSOG6o2kgVsOibg!5e0!3m2!1svi!2s!4v1761810691293!5m2!1svi!2s',
 'Đèo ven biển hùng vĩ, cảnh núi – biển ngoạn mục.',
 'anh/Đèo hải vân.jpg','thiennhien'),


('Nhà thờ Con Gà (Chính Tòa)','156 Trần Phú, Hải Châu 1, Hải Châu, Đà Nẵng',
 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3833.9700329665793!2d108.22061107602138!3d16.06704478461205!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3142198f38589aef%3A0x9409e1a4c30cbf79!2zR2nDoW8gWOG7qSBDaMOhbmggVG_DoCDEkMOgIE7hurVuZw!5e0!3m2!1svi!2s!4v1761810737600!5m2!1svi!2s',
 'Nhà thờ phong cách Gothic; biểu tượng tôn giáo giữa trung tâm thành phố.',
 'anh/Nhaga.jpg','congtrinh'),


('Thành Điện Hải','24 Trần Phú, Thạch Thang, Hải Châu, Đà Nẵng',
 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3833.788401887728!2d108.22057677602169!3d16.076466484604094!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3142183a92ad5d85%3A0x8c69a6b9b807240!2zxJAuIFRow6BuaCDEkGnhu4duIEjhuqNpLCBUaOG6oWNoIFRoYW5nLCBI4bqjaSBDaMOidSwgxJDDoCBO4bq1bmcgNTUwMDAwLCBWaeG7h3QgTmFt!5e0!3m2!1svi!2s!4v1761810765026!5m2!1svi!2s',
 'Di tích quân sự thế kỷ 19 (triều Nguyễn).',
 'anh/Dienhai.jpg','vanhoa'),


('Biển Mỹ An','Võ Nguyên Giáp, Bắc Mỹ An, Ngũ Hành Sơn, Đà Nẵng',
 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3834.2587322368017!2d108.24623597602128!3d16.052058084625006!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3142179546cbaa2b%3A0x4d10339bf79eb2da!2zQmnhu4NuIE3hu7kgQW4!5e0!3m2!1svi!2s!4v1761810788059!5m2!1svi!2s',
 'Bãi biển yên tĩnh, nước trong – cát mịn; phù hợp thể thao nước.',
 'anh/BienMyAn.jpg','thiennhien'),


('Phố cổ Hội An','Tp. Hội An, Quảng Nam',
 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d122778.37554307416!2d108.3346721609736!3d15.918276472041798!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31420dd4e1353a7b%3A0xae336435edfcca3!2zVHAuIEjhu5lpIEFuLCBRdeG6o25nIE5hbSwgVmnhu4d0IE5hbQ!5e0!3m2!1svi!2s!4v1761810811793!5m2!1svi!2s',
 'Di sản thế giới: nhà cổ, chùa chiền, đèn lồng rực rỡ, ẩm thực phong phú.',
 'anh/HoiAn.jpg','vanhoa'),


('Làng cổ Phong Nam','Phong Nam, Hòa Vang, Đà Nẵng',
 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3835.5175720118987!2d108.18846138591775!3d15.986550445182221!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31421b3a022c381d%3A0xf1091f8a1ae04521!2zTMOgbmcgY-G7lSBQaG9uZyBOYW0!5e0!3m2!1svi!2s!4v1761810847059!5m2!1svi!2s',
 'Không gian làng cổ, nghề truyền thống; cảnh yên bình ven sông.',
 'anh/LangCo.webp','vanhoa'),


('Giếng Trời','Thôn Phú Túc, Hòa Phú, Hòa Vang, Đà Nẵng (khu rừng nguyên sinh)',
 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1917.2958165769212!2d107.98989635180024!3d16.034759897593265!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3141f6831262b307%3A0xa64c6ed6f98b579b!2zTmjhuqV0IMOUbmc!5e0!3m2!1svi!2s!4v1761810914581!5m2!1svi!2s',
 'Hồ nước xanh giữa núi đá vôi và rừng nguyên sinh; điểm trekking – tắm mát.',
 'anh/Gieng.jpg','thiennhien'),


('Thánh Địa Mỹ Sơn','Xã Duy Phú, H. Duy Xuyên, Quảng Nam',
 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d7679.470411485051!2d108.11780679537198!3d15.765141089484498!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x314200a30f3839eb%3A0xe7c8b131ea5e90b2!2zVGjDoW5oIMSR4buLYSBN4bu5IFPGoW4sIER1eSBYdXnDqm4sIFF14bqjbmcgTmFtLCBWaeG7h3QgTmFt!5e0!3m2!1svi!2s!4v1761810949303!5m2!1svi!2s',
 'Quần thể đền tháp Chăm Pa cổ giữa thung lũng; di sản văn hóa nổi tiếng.',
 'anh/ThanhDia.jpg','vanhoa'),


('Bãi Biển Nam Ô','Nam Ô, Hòa Hiệp Nam/Bắc, Liên Chiểu, Đà Nẵng',
 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3832.966860898239!2d108.12772667602239!3d16.119015084567717!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31422100615493c1%3A0xcc7829d30269c285!2zQsOjaSBU4bqvbSBOYW0gw5Q!5e0!3m2!1svi!2s!4v1761810996920!5m2!1svi!2s',
 'Bãi biển yên tĩnh, nước trong xanh; nổi tiếng rong rêu mùa xuân.',
 'anh/Namo.jpg','thiennhien'),


('Cầu Sông Hàn','Đường Bạch Đằng – Trần Hưng Đạo, Hải Châu–Sơn Trà, Đà Nẵng',
 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3833.8716261068953!2d108.22422117602156!3d16.072150084607742!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31421831cd8a36d1%3A0x384de766f6cc5a4e!2zQ-G6p3UgU8O0bmcgSMOgbg!5e0!3m2!1svi!2s!4v1761811022392!5m2!1svi!2s',
 'Cầu quay biểu tượng; đẹp lung linh khi lên đèn.',
 'anh/CauSongHan.jpg','congtrinh');


SELECT * FROM DIADANH;


INSERT INTO KHUNGHIDUONG 
(TenKND, DiaChiKND, MapLinkKND, LoaiKHD, MoTaKND, ImageKND)
VALUES
-- 1. Sun World Bà Nà Hills
(
    'Sun World Bà Nà Hills',
    'Thôn An Sơn, xã Hòa Ninh, huyện Hòa Vang, TP Đà Nẵng',
    'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d9344.564818548868!2d108.0239637961576!3d16.027234286432954!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3141f60e6b3844c9%3A0x5c53b85f61797909!2sSun%20World%20B%C3%A0%20N%C3%A0%20Hills!5e0!3m2!1svi!2s!4v1761386544078!5m2!1svi!2s',
    'thiennhien',
    'Sun World Bà Nà Hills là khu du lịch nổi tiếng với khí hậu mát mẻ quanh năm, kiến trúc châu Âu độc đáo, cáp treo hiện đại và Cầu Vàng nổi bật với bàn tay khổng lồ nâng đỡ. Du khách có thể tham quan Làng Pháp, thưởng thức ẩm thực đa dạng, tham gia các trò chơi giải trí và khám phá vườn hoa tuyệt đẹp.',
    'anh/banahills.jpg'
),

-- 2. Asia Park
(
    'Asia Park',
    'Số 1 Phan Đăng Lưu, phường Hòa Cường Bắc, quận Hải Châu, TP Đà Nẵng',
    'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3834.4674680522517!2d108.22305827602098!3d16.041213884634193!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x314219e7a191cc17%3A0xe60f91d4055e3074!2sDA%20NANG%20DOWNTOWN!5e0!3m2!1svi!2s!4v1761807407857!5m2!1svi!2s',
    'thamquan',
    'Asia Park là khu vui chơi giải trí hiện đại nổi bật với vòng quay Sun Wheel khổng lồ, nhiều trò chơi cảm giác mạnh, khu vực trò chơi dành cho trẻ em và các công trình kiến trúc lấy cảm hứng từ văn hóa châu Á. Du khách có thể tham gia trò chơi, chụp ảnh, thưởng thức ẩm thực và hòa mình vào không gian sôi động.',
    'anh/AsianPark.jpg'
),

-- 3. Sky36
(
    'Sky36',
    'Tầng 35–37, tòa nhà Novotel Danang Premier Han River, 36–38 Bạch Đằng, phường Thạch Thang, quận Hải Châu, TP Đà Nẵng',
    'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3833.774534854411!2d108.22126017602173!3d16.07718558460354!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3142183ae54d6015%3A0xa61168c6e58166a2!2sSky36%20Bar%20%26%20Dining!5e0!3m2!1svi!2s!4v1761807723127!5m2!1svi!2s',
    'thamquan',
    'Sky36 là bar và lounge cao nhất Đà Nẵng với tầm nhìn toàn cảnh thành phố. Du khách có thể thưởng thức cocktail, âm nhạc sôi động và ngắm cảnh đêm lung linh của thành phố bên sông Hàn.',
    'anh/Sky360.webp'
),

-- 4. Du lịch Sông Hàn
(
    'Du lịch Sông Hàn',
    'Dọc hai bờ sông Hàn, trung tâm TP Đà Nẵng',
    'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d30671.84988975057!2d108.20529037171396!3d16.066463465190893!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3142183d37f5d3d5%3A0xaa3ca36520c7af4c!2zU8O0bmcgSMOgbg!5e0!3m2!1svi!2s!4v1761807780504!5m2!1svi!2s',
    'thamquan',
    'Sông Hàn là biểu tượng của Đà Nẵng với nhiều cây cầu nổi tiếng. Du khách có thể đi du thuyền ngắm cảnh, thưởng thức ẩm thực và cảm nhận vẻ đẹp thành phố về đêm.',
    'anh/SongHan.webp'
),

-- 5. Núi Thần Tài
(
    'Núi Thần Tài',
    'Quốc lộ 14G, xã Hòa Phú, huyện Hòa Vang, TP Đà Nẵng',
    'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1917.9233624441138!2d108.01773288884141!3d15.969378796173947!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3141f79592c03133%3A0xcb326b87969bbea3!2sNui%20Than%20Tai-%20Ebisu%20Onsen%20Resort%20Da%20Nang!5e0!3m2!1svi!2s!4v1761807841818!5m2!1svi!2s',
    'thamquan',
    'Núi Thần Tài là khu du lịch sinh thái nổi tiếng với suối khoáng nóng, tắm bùn, spa và cảnh quan núi rừng hùng vĩ. Phù hợp cho gia đình và nhóm bạn muốn nghỉ ngơi và thư giãn.',
    'anh/Núi Thần Tài.png'
),

-- 6. VinWonders Nam Hội An
(
    'VinWonders Nam Hội An',
    'Xã Bình Minh, huyện Thăng Bình, tỉnh Quảng Nam (gần Đà Nẵng)',
    'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3839.3342878792523!2d108.40336798589529!3d15.78631784579725!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31420bfde49f11d1%3A0x49422ef9074e41ca!2sVinWonders%20Nam%20H%E1%BB%99i%20An!5e0!3m2!1svi!2s!4v1761807898735!5m2!1svi!2s',
    'thamquan',
    'VinWonders Nam Hội An là khu vui chơi giải trí hiện đại, quy mô lớn với hàng loạt trò chơi cảm giác mạnh, công viên nước, khu biểu diễn nghệ thuật và trải nghiệm văn hóa Hội An. Phù hợp cho gia đình và du khách muốn tận hưởng ngày vui chơi trọn vẹn.',
    'anh/vin.jpg'
),

-- 7. InterContinental Danang Sun Peninsula Resort
(
    'InterContinental Danang Sun Peninsula Resort',
    'Bán đảo Sơn Trà, TP Đà Nẵng',
    'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3832.9318215347967!2d108.30364047602232!3d16.120827384566155!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31423d9513ec9199%3A0x5ade8a2bd4daad0d!2sInterContinental%20Danang%20Sun%20Peninsula%20Resort!5e0!3m2!1svi!2s!4v1761807957589!5m2!1svi!2s',
    'nghiduong',
    'InterContinental Danang Sun Peninsula Resort là khu nghỉ dưỡng 5 sao sang trọng trên bán đảo Sơn Trà, nổi bật với kiến trúc độc đáo, tầm nhìn biển tuyệt đẹp và dịch vụ đẳng cấp quốc tế.',
    'anh/resort1.png'
),

-- 8. Furama Resort Danang
(
    'Furama Resort Danang',
    '105 Võ Nguyên Giáp, phường Khuê Mỹ, quận Ngũ Hành Sơn, TP Đà Nẵng',
    'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3834.491872097083!2d108.24849317602107!3d16.03994558463522!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31420fdbc8cc38ef%3A0x9a6a3e31121225d2!2sFurama%20Resort%20Danang!5e0!3m2!1svi!2s!4v1761808163074!5m2!1svi!2s',
    'nghiduong',
    'Furama Resort Danang là khu nghỉ dưỡng cao cấp bên bờ biển Mỹ Khê với kiến trúc sang trọng, hồ bơi rộng, dịch vụ tiện nghi 5 sao và nhiều hoạt động giải trí.',
    'anh/resort2.jpg'
),

-- 9. Hyatt Regency Danang Resort & Spa
(
    'Hyatt Regency Danang Resort & Spa',
    '05 Trường Sa, phường Hòa Hải, quận Ngũ Hành Sơn, TP Đà Nẵng',
    'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3835.0096282167615!2d108.26162297602069!3d16.013014284658382!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x314210cf6a42c159%3A0xaeb535cc2b736240!2sHyatt%20Regency%20Danang%20Resort%20and%20Spa!5e0!3m2!1svi!2s!4v1761808201027!5m2!1svi!2s',
    'nghiduong',
    'Hyatt Regency Danang Resort & Spa là khu nghỉ dưỡng sang trọng bên bờ biển Non Nước với kiến trúc hiện đại, hồ bơi ngoài trời, spa cao cấp và nhiều tiện nghi đẳng cấp.',
    'anh/resort3.webp'
),

-- 10. Naman Retreat
(
    'Naman Retreat',
    'Trường Sa, phường Hòa Hải, quận Ngũ Hành Sơn, TP Đà Nẵng',
    'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1917.940834853191!2d108.28214283884142!3d15.967554696174338!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x314210ffff49be83%3A0x4dc264a06ef8baa5!2sNaman%20Retreat!5e0!3m2!1svi!2s!4v1761808756039!5m2!1svi!2s',
    'nghiduong',
    'Naman Retreat là khu nghỉ dưỡng nổi tiếng với kiến trúc tre đặc trưng, hòa quyện thiên nhiên và không gian hiện đại. Mang đến trải nghiệm nghỉ dưỡng yên bình và sang trọng.',
    'anh/resort4.jpg'
),

-- 11. Pullman Danang Beach Resort
(
  'Pullman Danang Beach Resort',
  '101 Võ Nguyên Giáp, Ngũ Hành Sơn, Đà Nẵng',
  'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3834.47995989457!2d108.24751227602104!3d16.04056468463478!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x314217686dfcf229%3A0xa17b1ae3b14af658!2sPullman%20Danang%20Beach%20Resort!5e0!3m2!1svi!2s!4v1761808244473!5m2!1svi!2s',
  'nghiduong',
  'Pullman Danang Beach Resort là khu nghỉ dưỡng cao cấp ven biển Mỹ Khê, Đà Nẵng, với các phòng nghỉ sang trọng, hồ bơi rộng, spa thư giãn và các dịch vụ ẩm thực phong phú. Đây là điểm dừng chân lý tưởng cho kỳ nghỉ gia đình hoặc du lịch kết hợp công tác.',
  'anh/resort5.jpg'
),

-- 12. Premier Village Danang Resort
(
  'Premier Village Danang Resort (Managed by Accor)',
  '99 Võ Nguyên Giáp, Ngũ Hành Sơn, Đà Nẵng',
  'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3834.4506113664197!2d108.24817547602112!3d16.04208988463351!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x314217687f97d183%3A0xee49ef38ee262430!2zUHJlbWllciBWaWxsYWdlIMSQw6AgTuG6tW5nIFJlc29ydA!5e0!3m2!1svi!2s!4v1761808272373!5m2!1svi!2s',
  'nghiduong',
  'Khu nghỉ dưỡng sang trọng ven biển Đà Nẵng, với các biệt thự hướng biển, hồ bơi riêng, và dịch vụ cao cấp mang đến trải nghiệm nghỉ dưỡng đẳng cấp quốc tế.',
  'anh/resort6.jpg'
),

-- 13. Công viên Biển Đông
(
  'Công viên Biển Đông',
  'Võ Nguyên Giáp, Phước Mỹ, Sơn Trà, Đà Nẵng',
  'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3833.906957619848!2d108.24326187602153!3d16.070317284609338!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3142171781bc61df%3A0x88236faf8a5f84f8!2zQ8O0bmcgdmnDqm4gQmnhu4NuIMSQw7RuZw!5e0!3m2!1svi!2s!4v1761808305527!5m2!1svi!2s',
  'thiennhien',
  'Công viên ven biển nổi tiếng tại Đà Nẵng, nơi du khách có thể tản bộ, ngắm cảnh biển, thưởng thức ẩm thực đường phố và tham gia các hoạt động giải trí ngoài trời.',
  'anh/CongVien.jpg'
),

-- 14. Hòa Phú Thành
(
  'Khu du lịch sinh thái Hòa Phú Thành',
  'QL14G, Hòa Phú, Hòa Vang, Đà Nẵng',
  'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3849.4291750182256!2d107.99154042200647!3d15.955569627200813!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3141f77af7e9b92b%3A0xfaf4e097094874f3!2zSMOyYSBQaMO6IFRow6BuaCBUb3VyaXN0IC0gVHLGsOG7o3QgdGjDoWMgxJDDoCBO4bq1bmc!5e0!3m2!1svi!2s!4v1761808357278!5m2!1svi!2s',
  'thiennhien',
  'Khu du lịch sinh thái nằm ở ngoại ô Đà Nẵng, nổi bật với cảnh quan núi rừng tươi mát, suối thác, hồ nước trong xanh, thích hợp cho các hoạt động dã ngoại, cắm trại và team building.',
  'anh/HPT.jpg'
),

-- 15. Suối Hoa
(
  'Khu du lịch Suối Hoa',
  'Thôn Phú Túc, Hòa Phú, Hòa Vang, Đà Nẵng',
  'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3836.0567990196837!2d107.99244637601969!3d15.958409984705195!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3141f7e396263a2d%3A0xc5471820f69b2f53!2zS2h1IGR1IGzhu4tjaCBzaW5oIHRow6FpIFN14buRaSBIb2E!5e0!3m2!1svi!2s!4v1761808395156!5m2!1svi!2s',
  'thiennhien',
  'Khu du lịch nổi tiếng với suối trong xanh, vườn hoa rực rỡ và không gian yên tĩnh, thích hợp cho các chuyến dã ngoại, thư giãn và tham quan cùng gia đình.',
  'anh/SH.jpg'
),

-- 16. Rừng dừa Cẩm Thanh
(
  'Rừng dừa Cẩm Thanh',
  'Thôn Cồn Nhàn, Cẩm Thanh, Hội An, Quảng Nam',
  'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4774.610044886419!2d108.36964843832543!3d15.878690096735962!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31420dae60bab751%3A0x73703c9bc757a0c3!2zUuG7q25nIEThu6thIELhuqN5IE3huqt1!5e0!3m2!1svi!2s!4v1761808435977!5m2!1svi!2s',
  'thiennhien',
  'Rừng dừa nổi tiếng gần Hội An với những kênh rạch xanh mát, trải nghiệm thuyền tham quan, ngắm cảnh thiên nhiên hoang sơ và thưởng thức đặc sản địa phương.',
  'anh/Rừng.jpg'
),

-- 17. Công viên APEC
(
  'Công viên APEC Đà Nẵng',
  'Đường 2 Tháng 9, Bình Hiên, Hải Châu, Đà Nẵng',
  'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d30673.138145755496!2d108.18822457910159!3d16.058105500000007!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31421930165d7dcd%3A0xf9d555ad7f300ab3!2zQ8O0bmcgVmnDqm4gQVBFQw!5e0!3m2!1svi!2s!4v1761809359663!5m2!1svi!2s',
  'congtrinh',
  'Công viên APEC nằm bên bờ sông Hàn, được xây dựng để phục vụ Tuần lễ Cấp cao APEC 2017. Không gian rộng, nhiều bãi cỏ, kiến trúc đẹp, là điểm check in nổi bật tại Đà Nẵng.',
  'anh/APEC.png'
);

USE QuanLyDuLich;

-- ==========================================
-- INSERT 5 TOUR MỚI
-- ==========================================
-- Đã sửa lại LichTrinhTour để khớp 100% với tên trong CSDL
INSERT INTO TOUR (
    TenTour,
    MoTaTour,
    GiaTour,
    ThoiGianTour,
    DoiTuong,
    KhachSan,
    ImageTour,
    LichTrinhTour
) VALUES (
    'Khám phá Đà Nẵng – Biển, Núi, Cầu Rồng',
    'Tour khám phá toàn diện Đà Nẵng với biển Mỹ Khê, cầu Rồng, bán đảo Sơn Trà và đèo Hải Vân. Phù hợp cho nhóm bạn trẻ hoặc người lần đầu đến Đà Nẵng.',
    4000000,
    '3 ngày 2 đêm',
    'Nhóm bạn trẻ, người lần đầu đến Đà Nẵng',
    'Khách sạn 3★ trung tâm (gần cầu Rồng / Bạch Đằng)',
    'images/cau.jpg',
    'Ngày 1: Bãi Biển Mỹ Khê, Cầu Rồng, café APEC\nNgày 2: Bán Đảo Sơn Trà, Núi Ngũ Hành Sơn, chợ đêm\nNgày 3: Đèo Hải Vân, Hải sản Năm Đảnh'
),
(
    'Nghỉ dưỡng & Thiền tịnh',
    'Tour nghỉ dưỡng kết hợp thiền tịnh, thư giãn tại resort 5 sao với spa, yoga và suối khoáng Núi Thần Tài. Dành cho cặp đôi hoặc người cần nghỉ ngơi.',
    9000000,
    '3 ngày 2 đêm',
    'Cặp đôi, người cần thư giãn',
    'Resort 5★ như Naman Retreat / Hyatt / Pullman',
    'images/thiendinh.jpg',
    'Ngày 1: Check-in resort, spa, yoga\nNgày 2: Núi Thần Tài\nNgày 3: Café & mua đặc sản tại Chợ Hàn – Thiên đường của đồ lưu niệm và mua sắm'
),
(
    'Giải trí & Trải nghiệm',
    'Tour vui chơi và trải nghiệm với các điểm nổi bật như Asia Park, Bà Nà Hills, Sky36 và đặc sản Đà Nẵng. Lý tưởng cho nhóm bạn trẻ thích check-in.',
    7000000,
    '4 ngày 3 đêm',
    'Nhóm bạn trẻ, thích check-in',
    'Khách sạn 4★ trung tâm hoặc ven biển Mỹ Khê',
    'images/giaitri.jpg',
    'Ngày 1: Asia Park & Chợ đêm Helio – Thiên đường ẩm thực về đêm ở Đà Nẵng\nNgày 2: Sun World Bà Nà Hills – Cầu Vàng\nNgày 3: Bán Đảo Sơn Trà, Sky36, đặc sản Đà Nẵng'
),
(
    'Văn hóa – Tâm linh – Ẩm thực',
    'Tour trải nghiệm văn hóa và ẩm thực đặc sắc của Đà Nẵng, kết hợp tham quan chùa Linh Ứng, Ngũ Hành Sơn và thưởng thức món ngon địa phương.',
    5000000,
    '3 ngày 2 đêm',
    'Người lớn tuổi, yêu văn hóa',
    'Khách sạn 3★ gần sông Hàn',
    'images/chualinhung.jpg',
    'Ngày 1: Bảo tàng Chăm, Cầu Rồng\nNgày 2: Chùa Linh Ứng, Núi Ngũ Hành Sơn\nNgày 3: Chợ Cồn – Nơi mua bán tấp nập của người dân bản địa, café The Local Beans'
),
(
    'Nghỉ dưỡng cao cấp – Trải nghiệm 5 sao',
    'Tour nghỉ dưỡng đẳng cấp dành cho cặp đôi, tuần trăng mật hoặc khách cao cấp. Trải nghiệm tại resort 5 sao và du thuyền sông Hàn sang trọng.',
    15000000,
    '3 ngày 2 đêm',
    'Cặp đôi, tuần trăng mật, khách cao cấp',
    'InterContinental / Furama Resort',
    'images/hotel.jpg',
    'Ngày 1: Check-in resort 5★, spa & fine dining\nNgày 2: Sun World Bà Nà Hills – Cầu Vàng\nNgày 3: Du lịch Sông Hàn & tiễn sân bay'
);


-- ==========================================
-- 1. LIÊN KẾT TOUR VỚI ĐỊA DANH (TOUR_DIADANH)
-- ==========================================

-- Tour 1: 'Khám phá Đà Nẵng' (Mỹ Khê, Cầu Rồng, Sơn Trà, Ngũ Hành Sơn, Đèo Hải Vân [Không có ID])
INSERT INTO TOUR_DIADANH (MaTour, MaDiaDanh) VALUES (1, 1); -- Bãi biển Mỹ Khê
INSERT INTO TOUR_DIADANH (MaTour, MaDiaDanh) VALUES (1, 5); -- Cầu Rồng
INSERT INTO TOUR_DIADANH (MaTour, MaDiaDanh) VALUES (1, 3); -- Bán đảo Sơn Trà
INSERT INTO TOUR_DIADANH (MaTour, MaDiaDanh) VALUES (1, 2); -- Ngũ Hành Sơn
INSERT INTO TOUR_DIADANH (MaTour, MaDiaDanh) VALUES (1, 11); -- Liên kết Đèo Hải Vân

-- Tour 2: 'Nghỉ dưỡng & Thiền tịnh' (Núi Thần Tài [Không có ID], Chợ Hàn)
INSERT INTO TOUR_MONAN (MaTour, MaMonAn) VALUES (2, 34); -- Chợ Hàn

-- Tour 3: 'Giải trí & Trải nghiệm' (Asia Park, Bà Nà, Cầu Vàng, Sơn Trà, Sky36)
INSERT INTO TOUR_KND (MaTour, MaKND) VALUES (3, 2); -- Asia Park
INSERT INTO TOUR_KND (MaTour, MaKND) VALUES (3, 1);  -- Bà Nà Hills
INSERT INTO TOUR_DIADANH (MaTour, MaDiaDanh) VALUES (3, 4);  -- Cầu Vàng
INSERT INTO TOUR_DIADANH (MaTour, MaDiaDanh) VALUES (3, 3);  -- Bán đảo Sơn Trà
INSERT INTO TOUR_KND (MaTour, MaKND) VALUES (3, 3); -- Sky36

-- Tour 4: 'Văn hóa – Tâm linh' (Bảo tàng Chăm [Không có ID], Cầu Rồng, Chùa Linh Ứng, Ngũ Hành Sơn, Chợ Cồn [Không có ID])
INSERT INTO TOUR_DIADANH (MaTour, MaDiaDanh) VALUES (4, 5); -- Cầu Rồng
INSERT INTO TOUR_DIADANH (MaTour, MaDiaDanh) VALUES (4, 7); -- Chùa Linh Ứng
INSERT INTO TOUR_DIADANH (MaTour, MaDiaDanh) VALUES (4, 2); -- Ngũ Hành Sơn
INSERT INTO TOUR_DIADANH (MaTour, MaDiaDanh) VALUES (4, 10); -- Liên kết Bảo tàng Chăm
INSERT INTO TOUR_MONAN (MaTour, MaMonAn) VALUES (4, 32);    -- Liên kết Chợ Cồn

-- Tour 5: 'Nghỉ dưỡng cao cấp' (Bà Nà, Cầu Vàng, Sông Hàn)
INSERT INTO TOUR_KND (MaTour, MaKND) VALUES (5, 1); -- Bà Nà Hills
INSERT INTO TOUR_DIADANH (MaTour, MaDiaDanh) VALUES (5, 4); -- Cầu Vàng
INSERT INTO TOUR_KND (MaTour, MaKND) VALUES (5, 4); -- Sông Hàn


-- ==========================================
-- 2. LIÊN KẾT TOUR VỚI KHU NGHỈ DƯỠNG (TOUR_KND)
-- ==========================================

-- Tour 2: 'Nghỉ dưỡng & Thiền tịnh' (Hyatt, Pullman)
INSERT INTO TOUR_KND (MaTour, MaKND) VALUES (2, 9); -- Hyatt Regency
INSERT INTO TOUR_KND (MaTour, MaKND) VALUES (2, 11); -- Pullman Danang

-- Tour 5: 'Nghỉ dưỡng cao cấp' (InterContinental, Furama)
INSERT INTO TOUR_KND (MaTour, MaKND) VALUES (5, 7); -- InterContinental Danang
INSERT INTO TOUR_KND (MaTour, MaKND) VALUES (5, 8); -- Furama Resort


-- ==========================================
-- 3. LIÊN KẾT TOUR VỚI MÓN ĂN (TOUR_MONAN)
-- ==========================================

-- Tour 1: 'Khám phá Đà Nẵng' (Hải sản)
INSERT INTO TOUR_MONAN (MaTour, MaMonAn) VALUES (1, 14); -- Hải sản

-- Tour 3: 'Giải trí & Trải nghiệm' (Đặc sản Đà Nẵng - chung chung, có thể gán các món nổi bật)
INSERT INTO TOUR_MONAN (MaTour, MaMonAn) VALUES (3, 1); -- Mì Quảng
INSERT INTO TOUR_MONAN (MaTour, MaMonAn) VALUES (3, 5); -- Bánh tráng cuốn thịt heo



SELECT * FROM DIADANH;
SELECT * FROM MONAN;
SELECT * FROM KHUNGHIDUONG;


