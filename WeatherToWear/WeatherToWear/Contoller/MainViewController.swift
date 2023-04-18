//
//  MainViewController.swift
//  WeatherToWear
//
//  Created by Kodeeshwari Solanki on 2023-04-17.
//

import UIKit

class MainViewController: UIViewController {
    
    
    @IBOutlet weak var switchForThemeMode: UISwitch!
    override func viewDidLoad() {
        super.viewDidLoad()
        navigationItem.setHidesBackButton(true, animated: false)
    }
    
    
    
    @IBAction func swtichValueChanged(_ sender: Any) {
        if(switchForThemeMode.isOn){
            overrideUserInterfaceStyle = .dark
        }
        else {
            overrideUserInterfaceStyle = .light
        }
    }
    
}
