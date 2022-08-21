//
//  DailyViewController.swift
//  TYLibrary
//
//  Created by itirc on 2020/6/15.
//  Copyright © 2020 itirc. All rights reserved.
//

import UIKit

class DailyViewController: UIViewController,
UITableViewDelegate, UITableViewDataSource {
    
    
    @IBOutlet weak var myTableView: UITableView!
    func numberOfSections(in tableView: UITableView) -> Int {
        return info.count
    }
    func tableView(_ tableView: UITableView, titleForHeaderInSection section: Int) -> String? {
        let title = section == 0 ? "每日推薦" : "特色介紹"
        return title
    }
    func tableView(_ tableView: UITableView, numberOfRowsInSection section: Int) -> Int {
        return info[section].count
    }
    
    func tableView(_ tableView: UITableView, cellForRowAt indexPath: IndexPath) -> UITableViewCell {
        // 取得 tableView 目前使用的 cell
        let cell =
            tableView.dequeueReusableCell(
                withIdentifier: "Cell", for: indexPath as IndexPath) as
            UITableViewCell

        // 設置 Accessory 按鈕樣式
        if indexPath.section == 1 {

        }

        // 顯示的內容
        if let myLabel = cell.textLabel {
            myLabel.text =
              "\(info[indexPath.section][indexPath.row])"
        }

        return cell
        
    }
    
    
    var info = [
        ["書籍一","書籍二"],
//        ["花","宙","水","木","火","土"]
    ]
    override func viewDidLoad() {
        super.viewDidLoad()
        let fullScreenSize = UIScreen.main.bounds.size


        // 註冊 cell
        myTableView.register(
          UITableViewCell.self, forCellReuseIdentifier: "Cell")

        // 設置委任對象
        myTableView.delegate = self as! UITableViewDelegate
        myTableView.dataSource = self as! UITableViewDataSource

        // 分隔線的樣式
        myTableView.separatorStyle = .singleLine

        // 分隔線的間距 四個數值分別代表 上、左、下、右 的間距
        myTableView.separatorInset =
            UIEdgeInsets(top: 0, left: 20, bottom: 0, right: 20)

        // 是否可以點選 cell
        myTableView.allowsSelection = true

        // 是否可以多選 cell
        myTableView.allowsMultipleSelection = false

        // 加入到畫面中
        self.view.addSubview(myTableView)
    }
    /*
    // MARK: - Navigation

    // In a storyboard-based application, you will often want to do a little preparation before navigation
    override func prepare(for segue: UIStoryboardSegue, sender: Any?) {
        // Get the new view controller using segue.destination.
        // Pass the selected object to the new view controller.
    }
    */

}
